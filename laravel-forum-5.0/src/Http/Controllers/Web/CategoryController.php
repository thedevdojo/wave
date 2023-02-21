<?php

namespace TeamTeaTime\Forum\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\View;
use TeamTeaTime\Forum\Events\UserViewingCategory;
use TeamTeaTime\Forum\Events\UserViewingIndex;
use TeamTeaTime\Forum\Http\Requests\CreateCategory;
use TeamTeaTime\Forum\Http\Requests\DeleteCategory;
use TeamTeaTime\Forum\Http\Requests\UpdateCategory;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Support\CategoryPrivacy;
use TeamTeaTime\Forum\Support\Web\Forum;

class CategoryController extends BaseController
{
    public function index(Request $request): View
    {
        $categories = CategoryPrivacy::getFilteredTreeFor($request->user());

        if ($request->user() !== null) {
            UserViewingIndex::dispatch($request->user());
        }

        return ViewFactory::make('forum::category.index', compact('categories'));
    }

    public function show(Request $request): View
    {
        $category = $request->route('category');

        if (! $category->isAccessibleTo($request->user())) {
            abort(404);
        }

        if ($request->user() !== null) {
            UserViewingCategory::dispatch($request->user(), $category);
        }

        $privateAncestor = $request->user() && $request->user()->can('manageCategories')
            ? Category::defaultOrder()
                ->where('is_private', true)
                ->ancestorsOf($category->id)
                ->first()
            : [];

        $categories = $request->user() && $request->user()->can('moveCategories')
            ? Category::defaultOrder()
                ->with('children')
                ->where('accepts_threads', true)
                ->withDepth()
                ->get()
            : [];

        $threads = $request->user() && $request->user()->can('viewTrashedThreads')
            ? $category->threads()->withTrashed()
            : $category->threads();

        $threads = $threads
            ->with('firstPost', 'lastPost', 'firstPost.author', 'lastPost.author', 'lastPost.thread', 'author')
            ->orderBy('pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate();

        $selectableThreadIds = [];
        if ($request->user()) {
            if (Gate::any(['moveThreadsFrom', 'lockThreads', 'pinThreads'], $category)) {
                // There are no thread-specific abilities corresponding to these,
                // so we can include all of the threads for this page
                $selectableThreadIds = $threads->pluck('id')->toArray();
            } else {
                $canDeleteThreads = $request->user()->can('deleteThreads', $category);
                $canRestoreThreads = $request->user()->can('restoreThreads', $category);

                if ($canDeleteThreads || $canRestoreThreads) {
                    foreach ($threads as $thread) {
                        if (($canDeleteThreads && $request->user()->can('delete', $thread))
                            || $canRestoreThreads && $request->user()->can('restore', $thread)
                        ) {
                            $selectableThreadIds[] = $thread->id;
                        }
                    }
                }
            }
        }

        return ViewFactory::make('forum::category.show', compact('privateAncestor', 'categories', 'category', 'threads', 'selectableThreadIds'));
    }

    public function store(CreateCategory $request): RedirectResponse
    {
        $category = $request->fulfill();

        Forum::alert('success', 'categories.created');

        return new RedirectResponse(Forum::route('category.show', $category));
    }

    public function update(UpdateCategory $request): RedirectResponse
    {
        $category = $request->fulfill();

        if ($category === null) {
            return $this->invalidSelectionResponse();
        }

        Forum::alert('success', 'categories.updated', 1);

        return new RedirectResponse(Forum::route('category.show', $category));
    }

    public function delete(DeleteCategory $request): RedirectResponse
    {
        $request->fulfill();

        Forum::alert('success', 'categories.deleted', 1);

        return new RedirectResponse(Forum::route('index'));
    }

    public function manage(Request $request): View
    {
        $categories = Category::defaultOrder()->get();
        $categories->makeHidden(['_lft', '_rgt', 'thread_count', 'post_count']);

        return ViewFactory::make('forum::category.manage', ['categories' => $categories->toTree()]);
    }
}
