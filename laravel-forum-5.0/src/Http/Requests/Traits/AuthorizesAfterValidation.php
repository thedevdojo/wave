<?php

namespace TeamTeaTime\Forum\Http\Requests\Traits;

trait AuthorizesAfterValidation
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $validator->failed() && ! $this->authorizeValidated()) {
                $this->failedAuthorization();
            }
        });
    }

    abstract public function authorizeValidated(): bool;
}
