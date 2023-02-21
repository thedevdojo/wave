<?php

namespace TeamTeaTime\Forum\Http\Controllers\Web;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use TeamTeaTime\Forum\Support\Web\Forum;

abstract class BaseController extends Controller
{
    use AuthorizesRequests;

    protected function bulkActionResponse(int $rowsAffected, string $transKey): RedirectResponse
    {
        Forum::alert('success', $transKey, $rowsAffected);

        return redirect()->back();
    }

    protected function invalidSelectionResponse(): RedirectResponse
    {
        Forum::alert('warning', 'general.invalid_selection');

        return redirect()->back();
    }
}
