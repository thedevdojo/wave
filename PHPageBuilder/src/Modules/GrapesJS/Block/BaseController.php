<?php

namespace PHPageBuilder\Modules\GrapesJS\Block;

use PHPageBuilder\Contracts\PageContract;

class BaseController
{
    /**
     * @var BaseModel $model
     */
    protected $model;

    /**
     * @var PageContract $page
     */
    protected $page;

    /**
     * @var bool $forPageBuilder
     */
    protected $forPageBuilder;

    /**
     * Pass essential data to this BaseController instance.
     *
     * @param BaseModel $model
     * @param PageContract $page
     * @param bool $forPageBuilder
     */
    public function init(BaseModel $model, PageContract $page, $forPageBuilder = false)
    {
        $this->model = $model;
        $this->page = $page;
        $this->forPageBuilder = $forPageBuilder;
    }

    /**
     * Handle the current request.
     */
    public function handleRequest()
    {
    }

}
