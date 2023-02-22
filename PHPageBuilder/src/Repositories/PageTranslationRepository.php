<?php

namespace PHPageBuilder\Repositories;

use PHPageBuilder\Contracts\PageTranslationRepositoryContract;

class PageTranslationRepository extends BaseRepository implements PageTranslationRepositoryContract
{
    /**
     * The page translations database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The class that represents each page translation.
     *
     * @var string
     */
    protected $class;

    /**
     * PageTranslationRepository constructor.
     */
    public function __construct()
    {
        $this->table = empty(phpb_config('page.translation.table')) ? 'page_translations' : phpb_config('page.translation.table');
        parent::__construct();
        $this->class = phpb_instance('page.translation');
    }
}
