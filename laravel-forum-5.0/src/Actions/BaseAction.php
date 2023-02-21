<?php

namespace TeamTeaTime\Forum\Actions;

use Exception;
use Illuminate\Support\Facades\DB;

abstract class BaseAction
{
    abstract protected function transact();

    public function execute()
    {
        DB::beginTransaction();

        try {
            $result = $this->transact();
            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('An exception occurred during an Action transaction.', 0, $e);
        }
    }
}
