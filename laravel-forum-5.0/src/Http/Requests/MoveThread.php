<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\MoveThread as Action;
use TeamTeaTime\Forum\Events\UserMovedThread;
use TeamTeaTime\Forum\Http\Requests\Traits\AuthorizesAfterValidation;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;
use TeamTeaTime\Forum\Models\Category;

class MoveThread extends FormRequest implements FulfillableRequest
{
    use AuthorizesAfterValidation;

    private Category $destinationCategory;

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'int', 'exists:forum_categories,id'],
        ];
    }

    public function authorizeValidated(): bool
    {
        $thread = $this->route('thread');
        $destinationCategory = $this->getDestinationCategory();

        return $this->user()->can('moveThreadsFrom', $thread->category) && $this->user()->can('moveThreadsTo', $destinationCategory);
    }

    public function fulfill()
    {
        $thread = $this->route('thread');
        $sourceCategory = $thread->category;
        $destinationCategory = $this->getDestinationCategory();

        $action = new Action($thread, $destinationCategory);
        $thread = $action->execute();

        if (! $thread === null) {
            UserMovedThread::dispatch($this->user(), $thread, $sourceCategory, $destinationCategory);
        }

        return $thread;
    }

    private function getDestinationCategory(): Category
    {
        if (! isset($this->destinationCategory)) {
            $this->destinationCategory = Category::find($this->input('category_id'));
        }

        return $this->destinationCategory;
    }
}
