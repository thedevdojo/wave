<?php

namespace PHPageBuilder\Modules\GrapesJS;

use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\ThemeContract;
use PHPageBuilder\Modules\GrapesJS\Block\BlockRenderer;
use PHPageBuilder\ThemeBlock;
use Exception;
use PHPageBuilder\Extensions;

class PageRenderer
{
    /**
     * @var ThemeContract $theme
     */
    protected $theme;

    /**
     * @var PageContract $page
     */
    protected $page;

    /**
     * @var array $pageData
     */
    protected $pageData;

    /**
     * @var array $pageBlocksData
     */
    protected $pageBlocksData;

    /**
     * @var ShortcodeParser $shortcodeParser
     */
    protected $shortcodeParser;

    /**
     * @var bool $forPageBuilder
     */
    protected $forPageBuilder;

    /**
     * @var string $language
     */
    protected $language;

    /**
     * @var bool $canBeCached
     */
    public static $canBeCached;

    /**
     * The maximum number of minutes this page should be cached, one week by default.
     *
     * @var int $cacheLifetime
     */
    public static $cacheLifetime = 7*24*60;

    /**
     * PageRenderer constructor.
     *
     * @param ThemeContract $theme
     * @param PageContract $page
     * @param bool $forPageBuilder
     */
    public function __construct(ThemeContract $theme, PageContract $page, $forPageBuilder = false)
    {
        $this->theme = $theme;
        $this->page = $page;
        $this->pageData = $page->getBuilderData();
        $this->shortcodeParser = new ShortcodeParser($this);
        $this->setLanguage(phpb_current_language());
        $this->forPageBuilder = $forPageBuilder;
    }

    /**
     * Set which page language variant to use while rendering.
     *
     * @param $language
     */
    public function setLanguage($language)
    {
        // if the given language is unknown, default the set language to the first available language
        $blockKeysAreLanguages = true;
        $supportedLanguages = array_keys($this->pageData['blocks'] ?? []);
        // check whether keys are valid languages (renderPageBuilderBlock uses pageData without language data)
        foreach ($supportedLanguages as $supportedLanguage) {
            if (strlen($supportedLanguage) > 5) {
                $blockKeysAreLanguages = false;
                break;
            }
        }
        if ($blockKeysAreLanguages) {
            if (! in_array($language, $supportedLanguages)) {
                $language = $supportedLanguages[0] ?? $language;
            }
        }

        $this->language = $language;
        $this->pageBlocksData = $this->getStoredPageBlocksData();
        $this->shortcodeParser->setLanguage($language);
    }

    /**
     * Return the absolute path to the layout view of this page.
     *
     * @return string|null
     */
    public function getPageLayoutPath()
    {
        $layout = basename($this->page->getLayout());
        $layoutPath = $this->theme->getFolder() . '/layouts/' . $layout . '/view.php';

        if ($path = Extensions::getLayout($layout)) {
            $layoutPath = $path . '/view.php';
        }

        return file_exists($layoutPath) ? $layoutPath : null;
    }

    /**
     * Set whether the currently rendered page can be cached.
     *
     * @param bool $canBeCached
     * @param string|null $cacheLifetime
     */
    public static function setCanBeCached(bool $canBeCached, $cacheLifetime = null)
    {
        if (! $canBeCached || ($cacheLifetime && intval($cacheLifetime) <= 0)) {
            static::$canBeCached = false;
        } elseif ($cacheLifetime) {
            static::$cacheLifetime = min(static::$cacheLifetime, intval($cacheLifetime));
        }
    }

    /**
     * Return whether the rendered page can be cached.
     * I.e. no blocks were encountered with content that varies per page load.
     *
     * @return bool
     */
    public static function canBeCached(): bool
    {
        return static::$canBeCached ?? true;
    }

    /**
     * Return the maximum number of minutes the rendered page should be cached.
     *
     * @return int
     */
    public static function getCacheLifetime(): int
    {
        if (! static::canBeCached()) {
            return 0;
        }
        return static::$cacheLifetime;
    }

    /**
     * Return an array with for each block of this page the stored html and settings data.
     *
     * @return array
     */
    public function getStoredPageBlocksData()
    {
        return $this->pageData['blocks'][$this->language] ?? $this->pageData['blocks'] ?? [];
    }

    /**
     * Return the rendered version of the page.
     *
     * @return string
     * @throws Exception
     */
    public function render()
    {
        // init variables that should be accessible in the view
        $renderer = $this;
        $page = $this->page;
        if ($this->forPageBuilder) {
            $body = '<div phpb-content-container="true"></div>';
        } else {
            $body = $this->renderBody();
        }

        $layoutPath = $this->getPageLayoutPath();
        if ($layoutPath) {
            ob_start();
            require $layoutPath;
            $pageHtml = ob_get_contents();
            ob_end_clean();
        } else {
            $pageHtml = $body;
        }

        // parse any shortcodes present in the page layout
        $pageHtml = $this->parseShortcodes($pageHtml);

        return $pageHtml;
    }

    /**
     * Return the page body for display on the website.
     * The body contains all blocks which are put into the selected layout.
     *
     * @param int $mainContainerIndex
     * @return string
     * @throws Exception
     */
    public function renderBody($mainContainerIndex = 0)
    {
        $html = '';
        $data = $this->pageData;

        if (isset($data['html']) && is_array($data['html'])) {
            $html = $this->parseShortcodes($data['html'][$mainContainerIndex]);
            // render html for each content container, to ensure all rendered blocks are accessible in the pagebuilder
            if (phpb_in_editmode()) {
                foreach ($data['html'] as $contentContainerHtml) {
                    $this->parseShortcodes($contentContainerHtml);
                }
            }
        }
        // backwards compatibility, html stored for only one layout container (@todo: remove this at the first mayor version)
        if (isset($data['html']) && is_string($data['html'])) {
            $html = $this->parseShortcodes($data['html']);
        }

        // include any style changes made via the page builder
        if (isset($data['css'])) {
            $html .= '<style>' . $data['css'] . '</style>';
        }

        return $html;
    }

    /**
     * Return a fully rendered theme block (including children blocks) with the given slug, data instance id and data context.
     * This method is called while parsing shortcodes.
     *
     * @param $slug
     * @param null $id                  the id with which data for this block is stored
     * @param null $context
     * @param int $maxDepth
     * @return string
     * @throws Exception
     */
    public function renderBlock($slug, $id = null, $context = null, $maxDepth = 25)
    {
        $themeBlock = ($blockPath = Extensions::getBlock($slug)) 
                        ? new ThemeBlock($this->theme, $blockPath, true, $slug) 
                        : new ThemeBlock($this->theme, $slug);

        $id = $id ?? $themeBlock->getSlug();
        $context = $context[$id] ?? $this->pageBlocksData[$id] ?? [];

        $blockRenderer = new BlockRenderer($this->theme, $this->page, $this->forPageBuilder);
        $renderedBlock = $blockRenderer->render($themeBlock, $context ?? [], $id);

        // determine the context for rendering nested blocks
        // if the current block is a html block, the context starts again at full page data
        // if the current block is a dynamic block, use the nested block data inside the current block's context
        $context = $context['blocks'] ?? [];
        if ($themeBlock->isHtmlBlock()) {
            $context = $this->pageBlocksData;
        }

        return $this->shortcodeParser->doShortcodes($renderedBlock, $context, $maxDepth - 1);
    }

    /**
     * Parse the given html with shortcodes to fully rendered html.
     *
     * @param string $htmlWithShortcodes
     * @param array $context                    the data for each block to be used while parsing the shortcodes
     * @return string
     * @throws Exception
     */
    public function parseShortcodes(string $htmlWithShortcodes, $context = null)
    {
        $context = $context ?? $this->pageBlocksData;
        return $this->shortcodeParser->doShortcodes($htmlWithShortcodes, $context);
    }

    /**
     * Return this page's blocks data to be loaded into the page edited inside GrapesJS.
     *
     * @return array
     * @throws Exception
     */
    public function getPageBlocksData()
    {
        $initialLanguage = $this->language;

        // remove the already rendered blocks
        $this->shortcodeParser->resetRenderedBlocks();

        // create the structure of page blocks data for each language
        $pageBlocks = [];
        foreach (phpb_active_languages() as $languageCode => $languageTranslation) {
            $this->setLanguage($languageCode);

            // for the current language build up a structure of rendered versions and use the stored data for the other languages
            if ($languageCode === $initialLanguage) {
                $this->renderBody();
                $pageBlocks[$languageCode] = $this->shortcodeParser->getRenderedBlocks()[$languageCode] ?? [];
            } else {
                $pageBlocks[$languageCode] = $this->pageBlocksData;
            }

            if (empty($pageBlocks[$languageCode])) {
                $pageBlocks[$languageCode] = null;
            }
        }

        // revert to initial language
        $this->setLanguage($initialLanguage);

        // return the rendered html and settings for each block
        return $pageBlocks;
    }
}
