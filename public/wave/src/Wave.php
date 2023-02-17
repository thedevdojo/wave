<?php

namespace Wave;

class Wave
{
	public function routes()
    {
        require __DIR__.'/../routes/web.php';
    }

    public function api()
    {
    	require __DIR__.'/../routes/api.php';
    }

}