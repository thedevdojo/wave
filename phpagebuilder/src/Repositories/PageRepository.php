<?php

namespace PHPageBuilder\Repositories;

use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\PageRepositoryContract;
use Exception;

class PageRepository extends BaseRepository implements PageRepositoryContract
{
    /**
     * The pages database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The class that represents each page.
     *
     * @var string
     */
    protected $class;

    /**
     * PageRepository constructor.
     */
    public function __construct()
    {
        $this->table = empty(phpb_config('page.table')) ? 'pages' : phpb_config('page.table');
        parent::__construct();
        $this->class = phpb_instance('page');
    }

    /**
     * Create a new page.
     *
     * @param array $data
     * @return bool|object|null
     * @throws Exception
     */
    public function create(array $data)
    {
        foreach (['name', 'layout'] as $field) {
            if (! isset($data[$field]) || ! is_string($data[$field])) {
                return false;
            }
        }

        $page = parent::create([
            'name' => $data['name'],
            'layout' => $data['layout'],
        ]);
        if (! ($page instanceof PageContract)) {
            throw new Exception("Page not of type PageContract");
        }
        return $this->replaceTranslations($page, $data);
    }

    /**
     * Update the given page with the given updated data.
     *
     * @param $page
     * @param array $data
     * @return bool|object|null
     */
    public function update($page, array $data)
    {
        foreach (['name', 'layout'] as $field) {
            if (! isset($data[$field]) || ! is_string($data[$field])) {
                return false;
            }
        }

        $page->invalidateCache();
        $this->replaceTranslations($page, $data);

        return parent::update($page, [
            'name' => $data['name'],
            'layout' => $data['layout'],
        ]);
    }

    /**
     * Replace the translations of the given page by the given data.
     *
     * @param PageContract $page
     * @param array $data
     * @return bool
     */
    protected function replaceTranslations(PageContract $page, array $data)
    {
        $activeLanguages = phpb_active_languages();
        foreach (['title', 'meta_title', 'meta_description', 'route'] as $field) {
            foreach ($activeLanguages as $languageCode => $languageTranslation) {
                if (! isset($data[$field][$languageCode])) {
                    return false;
                }
            }
        }

        $pageTranslationRepository = new PageTranslationRepository;
        $pageTranslationRepository->destroyWhere(phpb_config('page.translation.foreign_key'), $page->getId());
        foreach ($activeLanguages as $languageCode => $languageTranslation) {
            $pageTranslationRepository->create([
                phpb_config('page.translation.foreign_key') => $page->getId(),
                'locale' => $languageCode,
                'title' => $data['title'][$languageCode],
                'meta_title' => $data['meta_title'][$languageCode],
                'meta_description' => $data['meta_description'][$languageCode],
                'route' => $data['route'][$languageCode],
            ]);
        }

        return true;
    }

    /**
     * Update the given page with the given updated page data
     *
     * @param $page
     * @param array $data
     * @return bool|object|null
     */
    public function updatePageData($page, array $data)
    {
        $page->invalidateCache();

        return parent::update($page, [
            'data' => json_encode($data),
        ]);
    }

    /**
     * Remove the given page from the database.
     *
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        $this->findWithId($id)->invalidateCache();

        return parent::destroy($id);
    }
}
