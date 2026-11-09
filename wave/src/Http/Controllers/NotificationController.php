<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function delete(Request $request, $id): JsonResponse
    {
        // Notification ids are UUIDs, and Postgres errors on comparing a uuid column to anything else
        $notification = Str::isUuid($id) ? auth()->user()->notifications()->where('id', $id)->first() : null;
        if ($notification) {
            $notification->delete();

            return response()->json(['type' => 'success', 'message' => 'Marked Notification as Read', 'listid' => $request->listid]);
        } else {
            return response()->json(['type' => 'error', 'message' => 'Could not find the specified notification.']);
        }
    }
}
