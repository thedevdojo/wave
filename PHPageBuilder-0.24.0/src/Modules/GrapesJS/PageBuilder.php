<?php

namespace PHPageBuilder\Modules\GrapesJS;

use PHPageBuilder\Contracts\PageBuilderContract;
use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\ThemeContract;
use PHPageBuilder\Modules\GrapesJS\Block\BlockAdapter;
use PHPageBuilder\Modules\GrapesJS\Thumb\ThumbGenerator;
use PHPageBuilder\Modules\GrapesJS\Upload\Uploader;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Repositories\UploadRepository;
use Exception;
use PHPageBuilder\Extensions;

class PageBuilder implements PageBuilderContract
{
    /**
     * @var ThemeContract $theme
     */
    protected $theme;

    /**
     * @var array $scripts
     */
    protected $scripts = [];

    /**
     * @var string $css
     */
    protected $css;

    /**
     * PageBuilder constructor.
     */
    public function __construct()
    {
        $this->theme = phpb_instance('theme', [
            phpb_config('theme'), 
            phpb_config('theme.active_theme')
        ]);
    }

    /**
     * Set the theme used while rendering pages in the page builder.
     *
     * @param ThemeContract $theme
     */
    public function setTheme(ThemeContract $theme)
    {
        $this->theme = $theme;
    }

    /**
     * Process the current GET or POST request and redirect or render the requested page.
     *
     * @param $route
     * @param $action
     * @param PageContract|null $page
     * @return bool
     * @throws Exception
     */
    public function handleRequest($route, $action, PageContract $page = null)
    {
        phpb_set_in_editmode();

        if ($route === 'thumb_generator') {
            $thumbGenerator = new ThumbGenerator($this->theme);
            return $thumbGenerator->handleThumbRequest($action);
        }

        if (is_null($page)) {
            $pageId = $_GET['page'] ?? null;
            $pageRepository = new PageRepository;
            $page = $pageRepository->findWithId($pageId);
        }
        if (! ($page instanceof PageContract)) {
            return false;
        }

        switch ($action) {
            case null:
            case 'edit':
                $this->renderPageBuilder($page);
                exit();
                break;
            case 'store':
                if (isset($_POST) && isset($_POST['data'])) {
                    $data = json_decode($_POST['data'], true);
                    $this->updatePage($page, $data);
                    exit();
                }
                break;
            case 'upload':
                if (isset($_FILES)) {
                    $this->handleFileUpload();
                }
                break;
            case 'upload_delete':
                if (isset($_POST['id'])) {
                    $this->handleFileDelete();
                }
                break;
            case 'renderBlock':
                if (isset($_POST['language']) && isset($_POST['data']) && isset(phpb_active_languages()[$_POST['language']])) {
                    $this->renderPageBuilderBlock($page, $_POST['language'], json_decode($_POST['data'], true));
                    exit();
                }
                break;
            case 'renderLanguageVariant':
                if (isset($_POST['language']) && isset($_POST['data']) && isset(phpb_active_languages()[$_POST['language']])) {
                    $this->renderLanguageVariant($page, $_POST['language'], json_decode($_POST['data'], true));
                    exit();
                }
                break;
        }

        return false;
    }

    /**
     * Handle uploading of the posted file.
     *
     * @throws Exception
     */
    public function handleFileUpload()
    {
        $publicId = sha1(uniqid(rand(), true));
        $uploader = phpb_instance(Uploader::class, ['files']);
        $uploader
            ->file_name($publicId . '/' . str_replace(' ', '-', $uploader->file_src_name))
            ->upload_to(phpb_config('storage.uploads_folder') . '/')
            ->run();

        if (! $uploader->was_uploaded) {
            die("Upload error: {$uploader->error}");
        } else {
            $originalFile = str_replace(' ', '-', $uploader->file_src_name);
            $originalMime = $uploader->file_src_mime;
            $serverFile = $uploader->final_file_name;

            $uploadRepository = new UploadRepository;
            $uploadedFile = $uploadRepository->create([
                'public_id' => $publicId,
                'original_file' => $originalFile,
                'mime_type' => $originalMime,
                'server_file' => $serverFile
            ]);

            echo json_encode([
                'data' => [
                    'public_id' => $publicId,
                    'src' => $uploadedFile->getUrl(),
                    'type' => 'image'
                ]
            ]);
            exit();
        }
    }

    /**
     * Handle deleting of the posted previously uploaded file.
     */
    public function handleFileDelete()
    {
        $uploadRepository = new UploadRepository;
        $uploadedFileResult = $uploadRepository->findWhere('public_id', $_POST['id']);
        if (empty($uploadedFileResult)) {
            echo json_encode([
                'success' => false,
                'message' => 'File not found'
            ]);
            exit();
        }

        $uploadedFile = $uploadedFileResult[0];
        $uploadRepository->destroy($uploadedFile->id);

        $serverFilePath = realpath(phpb_config('storage.uploads_folder') . '/' . $uploadedFile->server_file);
        if ($serverFilePath) {
            unlink($serverFilePath);
        }

        $parentDirectory = realpath(phpb_config('storage.uploads_folder') . '/' . dirname($uploadedFile->server_file));
        if ($parentDirectory && dirname($uploadedFile->server_file) !== '.') {
            rmdir($parentDirectory);
        }

        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    /**
     * Render the PageBuilder for the given page.
     *
     * @param PageContract $page
     * @throws Exception
     */
    public function renderPageBuilder(PageContract $page)
    {
        phpb_set_in_editmode();

        // init variables that should be accessible in the view
        $pageBuilder = $this;
        $pageRenderer = phpb_instance(PageRenderer::class, [$this->theme, $page, true]);

        // create an array of theme blocks and theme block settings for in the page builder sidebar
        $blocks = [];
        $blockSettings = [];
        foreach ($this->theme->getThemeBlocks() as $themeBlock) {
            $slug = phpb_e($themeBlock->getSlug());
            $adapter = phpb_instance(BlockAdapter::class, [$pageRenderer, $themeBlock]);
            $blockSettings[$slug] = $adapter->getBlockSettingsArray();

            if ($themeBlock->get('hidden') !== true) {
                $blocks[$slug] = $adapter->getBlockManagerArray();
            }
        }

        // create an array of all uploaded assets
        $assets = [];
        foreach ((new UploadRepository)->getAll() as $file) {
            $assets[] = [
                'src' => $file->getUrl(),
                'public_id' => $file->public_id
            ];
        }

        require __DIR__ . '/resources/views/layout.php';
    }

    /**
     * Render the given page.
     *
     * @param PageContract $page
     * @param null $language
     * @return string
     */
    public function renderPage(PageContract $page, $language = null): string
    {
        $pageRenderer = phpb_instance(PageRenderer::class, [$this->theme, $page]);
        if (! is_null($language)) {
            $pageRenderer->setLanguage($language);
        }
        return $pageRenderer->render();
    }

    /**
     * Render in context of the given page, the given block with the passed settings, for updating the page builder.
     *
     * @param PageContract $page
     * @param string $language
     * @param array $blockData
     * @throws Exception
     */
    public function renderPageBuilderBlock(PageContract $page, string $language, $blockData = [])
    {
        phpb_set_in_editmode();

        $blockData = is_array($blockData) ? $blockData : [];
        $page->setData(['data' => $blockData], false);

        $pageRenderer = phpb_instance(PageRenderer::class, [$this->theme, $page, true]);
        $pageRenderer->setLanguage($language);
        echo $pageRenderer->parseShortcodes($blockData['html'], $blockData['blocks']);
    }

    /**
     * Render the given page in the given language using the given block data.
     *
     * @param PageContract $page
     * @param string $language
     * @param array $blockData
     * @throws Exception
     */
    public function renderLanguageVariant(PageContract $page, string $language, $blockData = [])
    {
        phpb_set_in_editmode();

        $blockData = is_array($blockData) ? $blockData : [];
        $page->setData(['data' => $blockData], false);

        $pageRenderer = phpb_instance(PageRenderer::class, [$this->theme, $page, true]);
        $pageRenderer->setLanguage($language);
        echo json_encode([
            'dynamicBlocks' => $pageRenderer->getPageBlocksData()[$language]
        ]);
    }

    /**
     * Update the given page with the given data (an array of html blocks).
     *
     * @param PageContract $page
     * @param $data
     * @return bool|object|null
     */
    public function updatePage(PageContract $page, $data)
    {
        $pageRepository = new PageRepository;
        return $pageRepository->updatePageData($page, $data);
    }

    /**
     * Return the list of all pages, used in CKEditor link editor.
     *
     * @return array
     */
    public function getPages()
    {
        $pages = [];

        $pageRepository = new PageRepository;
        foreach ($pageRepository->getAll() as $page) {
            $pages[] = [
                phpb_e($page->getName()),
                phpb_e($page->getId())
            ];
        }

        return $pages;
    }

    /**
     * Return this page's components in the format passed to GrapesJS.
     *
     * @param PageContract $page
     * @return array
     */
    public function getPageComponents(PageContract $page)
    {
        $data = $page->getBuilderData();
        $components = $data['components'] ?? [0 => []];
        // backwards compatibility, components are now stored for each main container (@todo: remove this at the first mayor version)
        if (isset($components[0]) && ! empty($components[0]) && ! isset($components[0][0])) {
            $components = [0 => $components];
        }
        return $components;
    }

    /**
     * Return this page's style in the format passed to GrapesJS.
     *
     * @param PageContract $page
     * @return array
     */
    public function getPageStyleComponents(PageContract $page)
    {
        $data = $page->getBuilderData();
        if (isset($data['style'])) {
            return $data['style'];
        }
        return [];
    }

    /**
     * Get or set custom css for customizing layout of the page builder.
     *
     * @param string|null $css
     * @return string
     */
    public function customStyle(string $css = null)
    {
        if (! is_null($css)) {
            $this->css = $css;
        }
        return $this->css;
    }

    /**
     * Get or set custom scripts for customizing behaviour of the page builder.
     *
     * @param string $location              head|body
     * @param string|null $scripts
     * @return string
     */
    public function customScripts(string $location, string $scripts = null)
    {
        if (! is_null($scripts)) {
            $this->scripts[$location] = $scripts;
        }
        return $this->scripts[$location] ?? '';
    }
}
