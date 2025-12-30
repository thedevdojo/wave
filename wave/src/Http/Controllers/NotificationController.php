<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    public function delete(Request $request, $id): JsonResponse
    {
        // Validate UUID format
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Invalid notification ID format.',
            ], 400);
        }

        // Fetch the notification for the authenticated user
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        // Return 404 if notification not found or doesn't belong to user
        if (! $notification) {
            return response()->json([
                'type' => 'error',
                'message' => 'Notification not found.',
            ], 404);
        }

        // Delete the notification
        $notification->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'Marked Notification as Read',
            'listid' => $request->listid,
        ]);
    }
}
