<?php

namespace TeamTeaTime\Forum\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

abstract class BaseController
{
    use AuthorizesRequests;

    protected function invalidSelectionResponse(): Response
    {
        return new Response([
            'success' => false,
            'message' => trans('forum::general.invalid_selection'),
        ], 400);
    }

    protected function notFoundResponse(): Response
    {
        return new Response(null, 404);
    }

    protected function bulkActionResponse(int $rowsAffected, string $transKey): Response
    {
        return new Response([
            'success' => true,
            'rows_affected' => $rowsAffected,
            'message' => trans_choice("forum::{$transKey}", $rowsAffected),
        ], 200);
    }
}
