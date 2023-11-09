<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Wave\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::paginate(10);
        return view('theme::announcements.index', compact('announcements'));
    }

    public function announcement($id)
    {
        $announcement = Announcement::find($id);
        return view('theme::announcements.announcement', compact('announcement'));
    }

    public function read()
    {
        $user = auth()->user();
        Announcement::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get()
            ->pluck('id')
            ->tap(function ($missingAnnouncements) use ($user) {
                $user->announcements()->attach($missingAnnouncements->toArray());
            });
    }
}
