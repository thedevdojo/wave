<?php

namespace PHPageBuilder\Contracts;

interface ThemeContract
{
    /**
     * Return all blocks of this theme.
     *
     * @return array        array of ThemeBlock instances
     */
    public function getThemeBlocks();

    /**
     * Return all layouts of this theme.
     *
     * @return array        array of ThemeLayout instances
     */
    public function getThemeLayouts();

    /**
     * Return the folder of this theme.
     *
     * @return mixed
     */
    public function getFolder();
}
