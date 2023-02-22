<?php

namespace PHPageBuilder\Contracts;

interface CacheContract
{
    /**
     * Return the cached page content for the given relative URL.
     *
     * @param string $relativeUrl
     * @return string|null
     */
    public function getForUrl(string $relativeUrl);

    /**
     * Store the given page content for the given relative URL.
     *
     * @param string $relativeUrl
     * @param string $pageContent
     * @param int $cacheLifetime
     */
    public function storeForUrl(string $relativeUrl, string $pageContent, int $cacheLifetime);

    /**
     * Return the cache storage path for the given relative URL.
     *
     * @param string $relativeUrl
     * @param bool $returnRelative
     * @return string
     */
    public function getPathForUrl(string $relativeUrl, bool $returnRelative = false): string;

    /**
     * Analyse the given cache path to determine whether it can be to used, without server/disk space issues.
     * This prevents deep nested cache paths and large numbers of cached pages per path due to query string variations.
     *
     * @param string $cachePath
     * @return bool
     */
    public function cachePathCanBeUsed(string $cachePath): bool;

    /**
     * Invalidate all variants stored for the given page route (i.e an URL with * and {} placeholders).
     *
     * @param string $route
     */
    public function invalidate(string $route);
}
