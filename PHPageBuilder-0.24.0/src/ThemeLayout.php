<?php

namespace PHPageBuilder;

use PHPageBuilder\Contracts\ThemeContract;

class ThemeLayout
{
    /**
     * @var $config
     */
    protected $config;

    /**
     * @var ThemeContract $theme
     */
    protected $theme;

    /**
     * @var string $layoutSlug
     */
    protected $layoutSlug;

    /**
     * @var bool $isExtension
     * Determines if a block was registered by an extension.
     */
    protected $isExtension;

    /**
     * @var bool $extensionSlug
     * Custom slug in case of extension.
     */
    protected $extensionSlug;

    /**
     * Theme ThemeLayout.
     *
     * @param ThemeContract $theme         the theme this layout belongs to
     * @param string $layoutSlug
     */
    public function __construct(ThemeContract $theme, string $layoutSlug, bool $isExtension = false, string $extensionSlug = null)
    {
        $this->theme = $theme;
        $this->layoutSlug = $layoutSlug;
        $this->isExtension = $isExtension;
        $this->extensionSlug = $extensionSlug;

        $this->config = [];
        if (file_exists($this->getFolder() . '/config.php')) {
            $this->config = include $this->getFolder() . '/config.php';
        }
    }

    /**
     * Return the absolute folder path of this theme layout.
     *
     * @return string
     */
    public function getFolder()
    {
        return (! $this->isExtension) ? ($this->theme->getFolder() . '/layouts/' . $this->layoutSlug) : $this->layoutSlug;
    }

    /**
     * Return the view file of this theme layout.
     *
     * @return string
     */
    public function getViewFile()
    {
        return $this->getFolder() . '/view.php';
    }

    /**
     * Return the slug identifying this type of layout.
     *
     * @return string
     */
    public function getSlug()
    {
        return !$this->isExtension ? $this->layoutSlug : $this->extensionSlug;
    }

    /**
     * Return the title of this theme layout.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->get('title') ?? ucfirst($this->getSlug());
    }

    /**
     * Return configuration with the given key (as dot-separated multidimensional array selector).
     *
     * @param $key
     * @return mixed|string
     */
    public function get($key)
    {
        // if no dot notation is used, return first dimension value or empty string
        if (strpos($key, '.') === false) {
            return $this->config[$key] ?? null;
        }

        // if dot notation is used, traverse config string
        $segments = explode('.', $key);
        $subArray = $this->config;
        foreach ($segments as $segment) {
            if (isset($subArray[$segment])) {
                $subArray = &$subArray[$segment];
            } else {
                return null;
            }
        }

        return $subArray;
    }
}
