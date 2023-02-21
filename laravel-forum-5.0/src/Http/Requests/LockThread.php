<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\LockThread as Action;
use TeamTeaTime\Forum\Events\UserLockedThread;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;

class LockThread extends FormRequest implements FulfillableRequest
{
    public function authorize(): bool
    {
        $thread = $this->route('thread');

        return $this->user()->can('lockThreads', $thread->category);
    }

    public function rules(): array
    {
        return [];
    }

    public function fulfill()
    {
        $action = new Action($this->route('thread'));
        $thread = $action->execute();

        if ($thread !== null) {
            UserLockedThread::dispatch($this->user(), $thread);
        }

        return $thread;
    }
}
