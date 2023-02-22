<?php

namespace PHPageBuilder\Contracts;

interface RouterContract
{
    /**
     * Return the page corresponding to the given URL.
     *
     * @param $url
     * @return PageTranslationContract|null
     */
    public function resolve($url);

    /**
     * Return the full page translation instance based on the given matched route or page translation id.
     * (this method is helpful when extending a router to perform additional checks after a route has been matched)
     *
     * @param string $matchedRoute                  the matched route
     * @param string $matchedPageTranslationId      the page translation id corresponding to the matched route
     * @return PageTranslationContract|null
     */
    public function getMatchedPage(string $matchedRoute, string $matchedPageTranslationId);

    /**
     * Order the given routes into the order in which they need to be evaluated.
     *
     * @param $allRoutes
     * @return array
     */
    public function getRoutesInOrder($allRoutes);

    /**
     * Compare two given routes and return -1,0,1 indicating which route should be evaluated first.
     *
     * @param $route1
     * @param $route2
     * @return int
     */
    public function routeOrderComparison($route1, $route2);
}
