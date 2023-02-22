<?php

namespace PHPageBuilder\Contracts;

interface PageBuilderContract
{
    /**
     * Process the current GET or POST request and redirect or render the requested page.
     *
     * @param $route
     * @param $action
     * @param PageContract|null $page
     */
    public function handleRequest($route, $action, PageContract $page = null);

    /**
     * Render the given page inside the PageBuilder.
     *
     * @param PageContract $page
     */
    public function renderPageBuilder(PageContract $page);

    /**
     * Render the given page.
     *
     * @param PageContract $page
     * @param null $language
     * @return string
     */
    public function renderPage(PageContract $page, $language = null): string;

    /**
     * Update the given page with the given data (an array of html blocks)
     *
     * @param PageContract $page
     * @param $data
     * @return bool|object|null
     */
    public function updatePage(PageContract $page, $data);

    /**
     * Get or set custom css for customizing layout of the page builder.
     *
     * @param string|null $css
     * @return string
     */
    public function customStyle(string $css = null);

    /**
     * Get or set custom scripts for customizing behaviour of the page builder.
     *
     * @param string $location              head|body
     * @param string|null $scripts
     * @return string
     */
    public function customScripts(string $location, string $scripts = null);

    /**
     * Set a theme for the page builder.
     * 
     * @param ThemeContract $theme
     */
    public function setTheme(ThemeContract $theme);
}
