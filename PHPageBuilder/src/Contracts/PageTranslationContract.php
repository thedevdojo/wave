<?php

namespace PHPageBuilder\Contracts;

interface PageTranslationContract
{
    /**
     * Return the page this translation belongs to.
     *
     * @return PageContract
     */
    public function getPage();
}
