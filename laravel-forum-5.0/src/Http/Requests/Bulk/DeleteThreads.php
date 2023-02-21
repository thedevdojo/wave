<?php

namespace TeamTeaTime\Forum\Http\Requests\Bulk;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\Bulk\DeleteThreads as Action;
use TeamTeaTime\Forum\Events\UserBulkDeletedThreads;
use TeamTeaTime\Forum\Http\Requests\Traits\AuthorizesAfterValidation;
use TeamTeaTime\Forum\Http\Requests\Traits\HandlesDeletion;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Support\CategoryPrivacy;

class DeleteThreads extends FormRequest implements FulfillableRequest
{
    use AuthorizesAfterValidation, HandlesDeletion;

    public function rules(): array
    {
        return [
            'threads' => ['required', 'array'],
            'permadelete' => ['boolean'],
        ];
    }

    public function authorizeValidated(): bool
    {
        // Eloquent is used here so that we get a collection of Thread instead of
        // stdClass in order for the gate to infer the policy to use.
        $threads = Thread::whereIn('id', $this->validated()['threads'])->with('category')->get();
        $accessibleCategoryIds = CategoryPrivacy::getFilteredFor($this->user())->keys();

        foreach ($threads as $thread) {
            $canView = $accessibleCategoryIds->contains($thread->category_id) && $this->user()->can('view', $thread);
            $canDelete = $this->user()->can('deleteThreads', $thread->category) && $this->user()->can('delete', $thread);

            if (! ($canView && $canDelete)) {
                return false;
            }
        }

        return true;
    }

    public function fulfill()
    {
        $action = new Action(
            $this->validated()['threads'],
            $this->user()->can('viewTrashedPosts'),
            $this->isPermaDeleting()
        );
        $threads = $action->execute();

        if ($threads !== null) {
            UserBulkDeletedThreads::dispatch($this->user(), $threads);
        }

        return $threads;
    }
}
