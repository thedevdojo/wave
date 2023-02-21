<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\RenameThread as Action;
use TeamTeaTime\Forum\Events\UserRenamedThread;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;

class RenameThread extends FormRequest implements FulfillableRequest
{
    public function authorize(): bool
    {
        $thread = $this->route('thread');

        return $this->user()->can('rename', $thread);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:'.config('forum.general.validation.title_min')],
        ];
    }

    public function fulfill()
    {
        $action = new Action($this->route('thread'), $this->validated()['title']);
        $thread = $action->execute();

        UserRenamedThread::dispatch($this->user(), $thread);

        return $thread;
    }
}
