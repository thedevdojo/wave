<?php

namespace PHPageBuilder;

use PHPageBuilder\Contracts\CacheContract;

class Cache implements CacheContract
{
    public static $maxCacheDepth = 7;
    public static $maxCachedPageVariants = 50;

    /**
     * Return the cached page content for the given relative URL.
     *
     * @param string $relativeUrl
     * @return string|null
     */
    public function getForUrl(string $relativeUrl)
    {
        $currentPageCacheFolder = $this->getPathForUrl($relativeUrl);
        if (is_dir($currentPageCacheFolder)) {

            if (! file_exists($currentPageCacheFolder . '/expires_at.txt')) {
                $this->invalidate($relativeUrl);
                return null;
            }
            $expiresAt = file_get_contents($currentPageCacheFolder . '/expires_at.txt');
            if ($expiresAt < time()) {
                $this->invalidate($relativeUrl);
                return null;
            }

            return file_get_contents($currentPageCacheFolder . '/page.html');
        }

        return null;
    }

    /**
     * Store the given page content for the given relative URL.
     *
     * @param string $relativeUrl
     * @param string $pageContent
     * @param int $cacheLifetime
     */
    public function storeForUrl(string $relativeUrl, string $pageContent, int $cacheLifetime)
    {
        $currentPageCacheFolder = $this->getPathForUrl($relativeUrl, true);
        if (! $this->cachePathCanBeUsed($currentPageCacheFolder)) {
            return;
        }

        $currentPageCacheFolder = $this->relativeToFullCachePath($currentPageCacheFolder);
        if (! is_dir($currentPageCacheFolder)) {
            mkdir($currentPageCacheFolder, 0777, true);
        }
        file_put_contents($currentPageCacheFolder . '/page.html', $pageContent);
        file_put_contents($currentPageCacheFolder . '/url.txt', $relativeUrl);
        file_put_contents($currentPageCacheFolder . '/expires_at.txt', time() + (60 * $cacheLifetime));
    }

    /**
     * Return the cache storage path for the given relative URL.
     *
     * @param string $relativeUrl
     * @param bool $returnRelative
     * @return string
     */
    public function getPathForUrl(string $relativeUrl, bool $returnRelative = false): string
    {
        // map empty url to the - root folder
        $relativeUrl = (empty($relativeUrl) || $relativeUrl === '/') ? '-' : $relativeUrl;

        // use a cache path with folders based on the URL segments, to allow partial cache invalidation with a specific prefix
        $relativeUrlWithoutQueryString = explode('?', $relativeUrl)[0];
        $cachePath = phpb_slug($relativeUrlWithoutQueryString, true);

        // suffix the cache path with a hash of the exact relative URL, to prevent returning wrong content due to slug collisions
        $cachePath .= '/' . sha1($relativeUrl);

        return $returnRelative ? $cachePath : $this->relativeToFullCachePath($cachePath);
    }

    protected function relativeToFullCachePath(string $relativeCachePath): string
    {
        $cacheFolder = phpb_config('cache.folder');
        if (substr($relativeCachePath, 0, 1) !== '/') {
            $cacheFolder .= '/';
        }
        return $cacheFolder . $relativeCachePath;
    }

    /**
     * Analyse the given cache path to determine whether it can be used, without server/disk space issues.
     * This prevents deep nested cache paths and large numbers of cached pages per path due to query string variations.
     *
     * @param string $cachePath
     * @return bool
     */
    public function cachePathCanBeUsed(string $cachePath): bool
    {
        if (sizeof(explode('/', $cachePath)) > static::$maxCacheDepth) {
            return false;
        }

        $cachePathWithoutHash = dirname($this->relativeToFullCachePath($cachePath));
        $numberOfCachedPageVariants = count(glob("{$cachePathWithoutHash}/*", GLOB_ONLYDIR));
        if (is_dir($cachePathWithoutHash) && $numberOfCachedPageVariants >= static::$maxCachedPageVariants) {
            return false;
        }

        return true;
    }

    /**
     * Invalidate all variants stored for the given page route (i.e. a URL with * and {} placeholders).
     *
     * @param string $route
     */
    public function invalidate(string $route)
    {
        $staticUrlPrefix1 = explode('*', $route)[0];
        $staticUrlPrefix2 = explode('{', $route)[0];

        $shortestPrefix = $staticUrlPrefix1;
        if (strlen($staticUrlPrefix2) < strlen($staticUrlPrefix1)) {
            $shortestPrefix = $staticUrlPrefix2;
        }

        $cachePathPrefix = dirname($this->getPathForUrl($shortestPrefix));
        $this->removeDirectoryRecursive($cachePathPrefix);
    }

    /**
     * Recursively remove the directory of the given path and all its contents.
     *
     * @param $path
     * @return bool
     */
    protected function removeDirectoryRecursive($path) {
        // prevent removing data outside the cache folder
        if (strpos($path, '..') !== false || strpos($path, phpb_config('cache.folder')) !== 0) {
            return false;
        }
        if (! is_dir($path)) {
            return false;
        }

        $path = substr($path, -1) === '/' ? $path : $path . '/';
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->removeDirectoryRecursive($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);

        return true;
    }
}
