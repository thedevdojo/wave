<?php

namespace PHPageBuilder\Contracts;

interface WebsiteManagerContract
{
    /**
     * Process the current GET or POST request and redirect or render the requested page.
     *
     * @param $route
     * @param $action
     */
    public function handleRequest($route, $action);

    /**
     * Render the website manager overview page.
     */
    public function renderOverview();

    /**
     * Render the website manager page settings (add/edit page form).
     *
     * @param PageContract $page
     */
    public function renderPageSettings(PageContract $page = null);

    /**
     * Render the website manager menu settings (add/edit menu form).
     */
    public function renderMenuSettings();

    /**
     * Render the welcome page.
     */
    public function renderWelcomePage();
}
