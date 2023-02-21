<?php

namespace TeamTeaTime\Forum\Http\Controllers\Api\Bulk;

use Illuminate\Http\Response;
use TeamTeaTime\Forum\Http\Controllers\Api\BaseController;
use TeamTeaTime\Forum\Http\Requests\Bulk\DeletePosts;
use TeamTeaTime\Forum\Http\Requests\Bulk\RestorePosts;

class PostController extends BaseController
{
    public function delete(DeletePosts $request): Response
    {
        $result = $request->fulfill();

        if ($result === null) {
            return $this->invalidSelectionResponse();
        }

        return $this->bulkActionResponse($result->count(), 'posts.deleted');
    }

    public function restore(RestorePosts $request): Response
    {
        $result = $request->fulfill();

        if ($result === null) {
            return $this->invalidSelectionResponse();
        }

        return $this->bulkActionResponse($result->count(), 'posts.updated');
    }
}
