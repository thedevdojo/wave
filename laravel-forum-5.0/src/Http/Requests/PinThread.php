<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\PinThread as Action;
use TeamTeaTime\Forum\Events\UserPinnedThread;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;

class PinThread extends FormRequest implements FulfillableRequest
{
    public function authorize(): bool
    {
        $thread = $this->route('thread');

        return $this->user()->can('pinThreads', $thread->category);
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
            UserPinnedThread::dispatch($this->user(), $thread);
        }

        return $thread;
    }
}
