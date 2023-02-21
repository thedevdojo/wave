<?php

namespace TeamTeaTime\Forum\Http\Controllers\Api\Bulk;

use Illuminate\Http\Response;
use TeamTeaTime\Forum\Http\Requests\Bulk\ManageCategories;

class CategoryController
{
    public function manage(ManageCategories $request): Response
    {
        $request->fulfill();

        return new Response(['success' => true], 200);
    }
}
