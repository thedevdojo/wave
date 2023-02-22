<?php

namespace PHPageBuilder\Modules\Router;

use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\PageTranslationContract;
use PHPageBuilder\Contracts\RouterContract;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Repositories\PageTranslationRepository;

class DatabasePageRouter implements RouterContract
{
    /**
     * @var PageRepository $pageRepository
     */
    protected $pageRepository;

    /**
     * @var PageTranslationRepository $pageTranslationRepository
     */
    protected $pageTranslationRepository;

    /**
     * @var array $routeParameters
     */
    protected $routeParameters;

    /**
     * @var array $routeToPageTranslationIdMapping
     */
    protected $routeToPageTranslationIdMapping;

    /**
     * DatabasePageRouter constructor.
     */
    public function __construct()
    {
        $this->pageRepository = new PageRepository;
        $this->pageTranslationRepository = new PageTranslationRepository;
        $this->routeParameters = [];
        $this->routeToPageTranslationIdMapping = [];
    }

    /**
     * Return the page from database corresponding to the given URL.
     *
     * @param $url
     * @return PageTranslationContract|null
     */
    public function resolve($url)
    {
        // strip URL query parameters
        $url = explode('?', $url, 2)[0];
        // split URL into segments using / as separator
        $urlSegments = explode('/', $url);

        // request all routes and convert each to its segments using / as separator
        $pageTranslations = $this->pageTranslationRepository->getAll(['id', 'route']);
        $routes = [];
        foreach ($pageTranslations as $pageTranslation) {
            $route = $pageTranslation->route;
            $this->routeToPageTranslationIdMapping[$route] = $pageTranslation->id;
            $routeSegments = explode('/', $route);
            $routes[] = $routeSegments;
        }

        // sort routes into the order for evaluation
        $orderedRoutes = $this->getRoutesInOrder($routes);

        // match each route with current URL segments and return the corresponding page once we find a match
        foreach ($orderedRoutes as $routeSegments) {
            if ($this->onRoute($urlSegments, $routeSegments)) {
                $fullRoute = implode('/', $routeSegments);
                $matchedPage = $this->getMatchedPage($fullRoute, $this->routeToPageTranslationIdMapping[$fullRoute]);

                if ($matchedPage) {
                    global $phpb_route_parameters;
                    $phpb_route_parameters = $this->routeParameters;

                    return $matchedPage;
                }
            }
        }

        return null;
    }

    /**
     * Sort the given routes into the order in which they need to be evaluated.
     *
     * @param $allRoutes
     * @return array
     */
    public function getRoutesInOrder($allRoutes)
    {
        usort($allRoutes, [$this, "routeOrderComparison"]);
        return $allRoutes;
    }

    /**
     * Compare two given routes and return -1,0,1 indicating which route should be evaluated first.
     *
     * @param $route1
     * @param $route2
     * @return int
     */
    public function routeOrderComparison($route1, $route2)
    {
        // routes with more segments should be evaluated first
        if (sizeof($route1) > sizeof($route2)) {
            return -1;
        }
        if (sizeof($route1) < sizeof($route2)) {
            return 1;
        }

        // routes ending with (more) named parameters should be evaluated after exact matches, but before catch all
        $namedParameterCountRoute1 = substr_count(implode('/', $route1), '{');
        $namedParameterCountRoute2 = substr_count(implode('/', $route2), '{');
        if ($namedParameterCountRoute1 < $namedParameterCountRoute2) {
            return -1;
        }
        if ($namedParameterCountRoute1 > $namedParameterCountRoute2) {
            return 1;
        }

        // routes ending with a wildcard should be evaluated last (after exact matches or named parameters)
        if (array_slice($route1, -1)[0] === '*') {
            return 1;
        }
        if (array_slice($route2, -1)[0] === '*') {
            return -1;
        }

        // otherwise, the order is undetermined
        return 0;
    }

    /**
     * Return the full page translation instance based on the given matched route or page translation id.
     * (this method is helpful when extending a router to perform additional checks after a route has been matched)
     *
     * @param string $matchedRoute                  the matched route
     * @param string $matchedPageTranslationId      the page translation id corresponding to the matched route
     * @return PageTranslationContract|null
     */
    public function getMatchedPage(string $matchedRoute, string $matchedPageTranslationId)
    {
        $pageTranslation = $this->pageTranslationRepository->findWithId($matchedPageTranslationId);
        if ($pageTranslation instanceof PageTranslationContract) {
            return $pageTranslation;
        }
        return null;
    }

    /**
     * Return whether the given URL segments match with the given route segments.
     *
     * @param $urlSegments
     * @param $routeSegments
     * @return bool
     */
    protected function onRoute($urlSegments, $routeSegments)
    {
        // URL does not match if segment counts don't match, except if the route ends with a *
        if (sizeof($urlSegments) !== sizeof($routeSegments) && end($routeSegments) !== '*') {
            return false;
        }

        // try matching each route segment with the same level URL segment
        $routeParameters = [];
        foreach ($routeSegments as $i => $routeSegment) {
            if (! isset($urlSegments[$i])) {
                return false;
            }
            $urlSegment = $urlSegments[$i];

            // the URL segment matches if the route segment is a {parameter}
            if (substr($routeSegment,0, 1) === '{' && substr($routeSegment, -1) === '}') {
                $parameter = trim($routeSegment, '{}');
                $routeParameters[$parameter] = $urlSegment;
                continue;
            }
            // the URL fully matches if the route segment is a wildcard
            if ($routeSegment === '*') {
                break;
            }
            // the URL segment matches if equal to the route segment
            if ($urlSegment === $routeSegment) {
                continue;
            }

            // the URL segment and route segment did not match
            return false;
        }

        $this->routeParameters = $routeParameters;
        return true;
    }
}
