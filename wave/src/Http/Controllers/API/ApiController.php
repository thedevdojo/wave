<?php

namespace Wave\Http\Controllers\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Http\Controllers\ContentTypes\Text;
use TCG\Voyager\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use TCG\Voyager\Models\DataType;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

	use BreadRelationshipParser;
    //*********************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      API Browse our Data Type (B)READ
    //
    //*********************************************

    public function browse(Request $request, $slug)
    {
        $dataType = Datatype::where('slug', '=', $slug)->first();

    	$authorized = auth()->user()->can('browse', app($dataType->model_name));

    	if(!$authorized){
    		abort(403, 'Unauthorized');
    	}

    	// Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {

	    	$model = app($dataType->model_name);
	        $query = $model::select('*');

	        $relationships = $dataType->getRelationships([], $dataType);

	        // If a column has a relationship associated with it, we do not want to show that field
	        $this->removeRelationshipField($dataType, 'browse');

	        $dataTypeContent = call_user_func([$query->with($relationships)->orderBy($model->getKeyName(), 'DESC'), 'paginate']);

	        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

	    } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        return response()->json($dataTypeContent);
    }


    //*********************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  API Read an item of our Data Type B(R)EAD
    //
    //*********************************************

    public function read(Request $request, $slug, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Datatype::where('slug', '=', $slug)->first();

        $relationships = $dataType->getRelationships([], $dataType);

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return response()->json($dataTypeContent);

    }

    //*********************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  API Edit an item of our Data Type BR(E)AD
    //
    //*********************************************

    public function edit(Request $request, $slug, $id)
    {

        $slug = $this->getSlug($request);

        $dataType = Datatype::where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);


        // Check permission
        $this->authorize('edit', $data);

        if(!$this->isValidJson($request->getContent())){
            abort('400', 'Invalid JSON structure.');
        }


        $data = $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        if(isset($data)){
            return response()->json(['success' => true, 'data' => $data]);
        } else{
            abort('400', 'Could not update content, error with data received');
        }

    }


    //*********************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // API Add a new item of our Data Type BRE(A)D
    //
    //*********************************************

    public function add(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Datatype::where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        if(!$this->isValidJson($request->getContent())){
            abort('400', 'Invalid JSON structure.');
        }

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        if(isset($data)){
            return response()->json(['success' => true, 'data' => $data]);
        } else{
            abort('400', 'Could not add content, error with data received');
        }

    }


    //*********************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         API Delete an item BREA(D)
    //
    //*********************************************

    public function delete(Request $request, $slug, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Datatype::where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('delete', app($dataType->model_name));
        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $res = $data->delete($id);

        if ($res) {
            return response()->json(['success' => true, 'message' => 'Successfully deleted']);
        }

    }

    public function getSlug(Request $request)
    {
        if (isset($this->slug)) {
            $slug = $this->slug;
        } else {
            $slug = $request->segment(2);
        }

        return $slug;
    }

    public function insertUpdateData($request, $slug, $rows, $data)
    {

        foreach ($rows as $row) {

            $options = $row->details;

            $content = $this->getContentBasedOnType($request, $slug, $row, $options);

            if(isset($request->{$row->field})){
                $data->{$row->field} = $content;
            }
        }

        $data->save();

        return $data;
    }

    public function getContentBasedOnType(Request $request, $slug, $row, $options = null)
    {
        return (new Text($request, $slug, $row, $options))->handle();
    }

    private function isValidJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
    }

}
