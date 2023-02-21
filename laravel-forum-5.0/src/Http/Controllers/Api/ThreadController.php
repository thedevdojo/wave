<?php

namespace TeamTeaTime\Forum\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use TeamTeaTime\Forum\Http\Requests\CreateThread;
use TeamTeaTime\Forum\Http\Requests\DeleteThread;
use TeamTeaTime\Forum\Http\Requests\LockThread;
use TeamTeaTime\Forum\Http\Requests\MarkThreadsAsRead;
use TeamTeaTime\Forum\Http\Requests\MoveThread;
use TeamTeaTime\Forum\Http\Requests\PinThread;
use TeamTeaTime\Forum\Http\Requests\RenameThread;
use TeamTeaTime\Forum\Http\Requests\RestoreThread;
use TeamTeaTime\Forum\Http\Requests\UnlockThread;
use TeamTeaTime\Forum\Http\Requests\UnpinThread;
use TeamTeaTime\Forum\Http\Resources\ThreadResource;
use TeamTeaTime\Forum\Models\Thread;

class ThreadController extends BaseController
{
    protected $resourceClass = null;

    public function __construct()
    {
        $this->resourceClass = config('forum.api.resources.thread', ThreadResource::class);
    }

    public function recent(Request $request, bool $unreadOnly = false): AnonymousResourceCollection
    {
        $threads = Thread::recent()
            ->get()
            ->filter(function ($thread) use ($request, $unreadOnly) {
                return $thread->category->isAccessibleTo($request->user())
                    && (! $unreadOnly || $thread->userReadStatus !== null)
                    && (
                        ! $thread->category->is_private
                        || $request->user()
                        && $request->user()->can('view', $thread)
                    );
            });

        return $this->resourceClass::collection($threads);
    }

    public function unread(Request $request): AnonymousResourceCollection
    {
        return $this->recent($request, true);
    }

    public function markAsRead(MarkThreadsAsRead $request): Response
    {
        $category = $request->fulfill();

        return new Response(['success' => true]);
    }

    public function indexByCategory(Request $request): AnonymousResourceCollection|Response
    {
        $category = $request->route('category');
        if (! $category->isAccessibleTo($request->user())) {
            return $this->notFoundResponse();
        }

        $query = Thread::orderBy('created_at')->where('category_id', $category->id);

        $createdAfter = $request->query('created_after');
        $createdBefore = $request->query('created_before');
        $updatedAfter = $request->query('updated_after');
        $updatedBefore = $request->query('updated_before');

        if ($createdAfter !== null) {
            $query = $query->where('created_at', '>', Carbon::parse($createdAfter)->toDateString());
        }
        if ($createdBefore !== null) {
            $query = $query->where('created_at', '<', Carbon::parse($createdBefore)->toDateString());
        }
        if ($updatedAfter !== null) {
            $query = $query->where('updated_at', '>', Carbon::parse($updatedAfter)->toDateString());
        }
        if ($updatedBefore !== null) {
            $query = $query->where('updated_at', '<', Carbon::parse($updatedBefore)->toDateString());
        }

        $threads = $query->paginate();

        if ($category->is_private) {
            $threads->setCollection($threads->getCollection()->filter(function ($thread) use ($request) {
                return $request->user() && $request->user()->can('view', $thread);
            }));
        }

        return $this->resourceClass::collection($threads);
    }

    public function store(CreateThread $request): JsonResource
    {
        $thread = $request->fulfill();

        return new $this->resourceClass($thread);
    }

    public function fetch(Request $request): JsonResource|Response
    {
        $thread = $request->route('thread');
        if (! $thread->category->isAccessibleTo($request->user())) {
            return $this->notFoundResponse();
        }

        if ($thread->category->is_private) {
            $this->authorize('view', $thread);
        }

        return new $this->resourceClass($thread);
    }

    public function lock(LockThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function unlock(UnlockThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function pin(PinThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function unpin(UnpinThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function rename(RenameThread $request): JsonResource
    {
        $thread = $request->fulfill();

        return new $this->resourceClass($thread);
    }

    public function move(MoveThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function delete(DeleteThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }

    public function restore(RestoreThread $request): JsonResource|Response
    {
        $thread = $request->fulfill();

        if ($thread === null) {
            return $this->invalidSelectionResponse();
        }

        return new $this->resourceClass($thread);
    }
}
