<?php

namespace Wave\Http\Controllers;

use Wave\Page;
use Illuminate\Http\Request;

class NotificationController extends \App\Http\Controllers\Controller
{
    public function index(){
        return view('theme::notifications.index');
    }

    public function delete(Request $request, $id){
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification){
            $notification->delete();
            return response()->json(['type' => 'success', 'message' => 'Marked Notification as Read', 'listid' => $request->listid]);
        }
        else {
            return response()->json(['type' => 'error', 'message' => 'Could not find the specified notification.']);
        }
    }
}
