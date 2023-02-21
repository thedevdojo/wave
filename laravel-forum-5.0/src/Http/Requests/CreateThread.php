<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\CreateThread as Action;
use TeamTeaTime\Forum\Events\UserCreatedThread;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;

class CreateThread extends FormRequest implements FulfillableRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');

        return $category->accepts_threads && $this->user()->can('createThreads', $category);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:'.config('forum.general.validation.title_min')],
            'content' => ['required', 'string', 'min:'.config('forum.general.validation.content_min')],
        ];
    }

    public function fulfill()
    {
        $input = $this->validated();
        $category = $this->route('category');

        $action = new Action($category, $this->user(), $input['title'], $input['content']);
        $thread = $action->execute();

        UserCreatedThread::dispatch($this->user(), $thread);

        return $thread;
    }
}
