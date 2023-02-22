<?php

namespace PHPageBuilder;

use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\PageTranslationContract;
use PHPageBuilder\Repositories\PageRepository;

class PageTranslation implements PageTranslationContract
{
    /**
     * Return the page this translation belongs to.
     *
     * @return PageContract
     */
    public function getPage()
    {
        $foreignKey = phpb_config('page.translation.foreign_key');
        return (new PageRepository)->findWithId($this->{$foreignKey});
    }
}
