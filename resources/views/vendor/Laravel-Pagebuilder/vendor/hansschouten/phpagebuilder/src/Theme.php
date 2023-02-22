<?php

namespace PHPageBuilder;

use DirectoryIterator;
use PHPageBuilder\Contracts\ThemeContract;

class Theme implements ThemeContract
{
    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var string $themeSlug
     */
    protected $themeSlug;

    /**
     * @var array $blocks
     */
    protected $blocks;

    /**
     * @var array $layouts
     */
    protected $layouts;

    /**
     * Theme constructor.
     *
     * @param array $config
     * @param string $themeSlug
     */

    public function __construct(array $config, string $themeSlug)
    {
        $this->config = $config;
        $this->themeSlug = $themeSlug;
    }

    /**
     * Load a single block entry
     */

    protected function attemptBlockRegistration($entry) {
        if ($entry->isDir() && ! $entry->isDot()) {
            $blockSlug = $entry->getFilename();
            $block = new ThemeBlock($this, $blockSlug);

            $isActive = true;
            foreach (($block->get('whitelist') ?? []) as $whitelistDomain) {
                $isActive = false;
                if (strpos(phpb_current_full_url(), $whitelistDomain) !== false) {
                    $isActive = true;
                    break;
                }
            }

            if ($isActive) {
                $this->blocks[$blockSlug] = $block;
            }
        }
    }

    /**
     * Load a single extension block entry
     */

    protected function attemptExtensionBlockRegistration($slug, $path) {
        if ($slug && $path) {
            $block = new ThemeBlock($this, $path, true, $slug);

            $isActive = true;
            foreach (($block->get('whitelist') ?? []) as $whitelistDomain) {
                $isActive = false;
                if (strpos(phpb_current_full_url(), $whitelistDomain) !== false) {
                    $isActive = true;
                    break;
                }
            }

            if ($isActive) {
                $this->blocks[$slug] = $block;
            }
        }
    }

    /**
     * Load a single layout entry
     */

    protected function attemptLayoutRegistration($entry) {
        if ($entry->isDir() && ! $entry->isDot()) {
            $layoutSlug = $entry->getFilename();
            $layout = new ThemeLayout($this, $layoutSlug);
            $this->layouts[$layoutSlug] = $layout;
        }
    }

    /**
     * Load a single layout entry
     */

    protected function attemptExtensionLayoutRegistration($slug, $path) {
        $layout = new ThemeLayout($this, $path, true, $slug);
        $this->layouts[$slug] = $layout;
    }

    /**
     * Load all blocks of the current theme.
     */
    protected function loadThemeBlocks()
    {
        $this->blocks = [];

        if (file_exists($this->getFolder() . '/blocks')) {
            $blocksDirectory = new DirectoryIterator($this->getFolder() . '/blocks');
            foreach ($blocksDirectory as $entry) {
                $this->attemptBlockRegistration($entry);
            }
        }

        foreach (Extensions::getBlocks() as $slug => $path) {
            $this->attemptExtensionBlockRegistration($slug, $path);
        }
    }

    /**
     * Load all layouts of the current theme.
     */
    protected function loadThemeLayouts()
    {
        $this->layouts = [];

        if (file_exists($this->getFolder() . '/layouts')) {
            $layoutsDirectory = new DirectoryIterator($this->getFolder() . '/layouts');
            foreach ($layoutsDirectory as $entry) {
                $this->attemptLayoutRegistration($entry);
            }
        }

        foreach (Extensions::getLayouts() as $slug => $path) {
            $this->attemptExtensionLayoutRegistration($slug, $path);
        }
    }

    /**
     * Return all blocks of this theme.
     *
     * @return array        array of ThemeBlock instances
     */
    public function getThemeBlocks()
    {
        $this->loadThemeBlocks();
        return $this->blocks;
    }

    /**
     * Return all layouts of this theme.
     *
     * @return array        array of ThemeLayout instances
     */
    public function getThemeLayouts()
    {
        $this->loadThemeLayouts();
        return $this->layouts;
    }

    /**
     * Return the absolute folder path of the theme passed to this Theme instance.
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->config['folder'] . '/' . basename($this->themeSlug);
    }
}
