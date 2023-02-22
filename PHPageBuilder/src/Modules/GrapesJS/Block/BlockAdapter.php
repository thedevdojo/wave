<?php

namespace PHPageBuilder\Modules\GrapesJS\Block;

use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\ThemeBlock;
use Exception;

/**
 * Class BlockAdapter
 *
 * Class for adapting a ThemeBlock into a JSON object understood by the GrapesJS page builder.
 *
 * @package PHPageBuilder\GrapesJS
 */
class BlockAdapter
{
    /**
     * @var PageRenderer $pageRenderer
     */
    protected $pageRenderer;

    /**
     * @var ThemeBlock $block
     */
    protected $block;

    /**
     * BlockAdapter constructor.
     *
     * @param PageRenderer $pageRenderer
     * @param ThemeBlock $block
     */
    public function __construct(PageRenderer $pageRenderer, ThemeBlock $block)
    {
        $this->pageRenderer = $pageRenderer;
        $this->block = $block;
    }

    /**
     * Return the slug identifying this type of theme block.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->block->getSlug();
    }

    /**
     * Return the visible title of this block.
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->block->get('title')) {
            return $this->block->get('title');
        }
        return str_replace('-', ' ', ucfirst($this->getSlug()));
    }

    /**
     * Return the category this block belongs to.
     *
     * @return string|null
     */
    public function getCategory()
    {
        if ($this->block->get('category')) {
            return $this->block->get('category');
        }
        return phpb_trans('pagebuilder.default-category');
    }

    /**
     * Return an array representation of the theme block, for adding as a block to GrapesJS.
     *
     * @return array
     * @throws Exception
     */
    public function getBlockManagerArray()
    {
        $content = $this->pageRenderer->renderBlock($this->block->getSlug());

        $img = '';
        if (file_exists($this->block->getThumbPath())) {
            $img = '<div class="block-thumb" style="background-image: url(' . phpb_full_url($this->block->getThumbUrl()) . '); background-size: cover"></div>';
        }

        $data = [
            'label' => $img . $this->getTitle(),
            'category' => $this->getCategory(),
            'content' => $content
        ];

        if (! $img) {
            $iconClass = 'fa fa-bars';
            if ($this->block->get('icon')) {
                $iconClass = $this->block->get('icon');
            }
            $data['attributes'] = ['class' => $iconClass];
        }

        return $data;
    }

    /**
     * Return the array of settings of the theme block, for populating the settings tab in the GrapesJS sidebar.
     *
     * @return array
     */
    public function getBlockSettingsArray()
    {
        $blockSettings = $this->block->get('settings');
        if ($this->block->isHtmlBlock() || ! is_array($blockSettings)) {
            return [];
        }

        $settings = [];
        foreach ($blockSettings as $name => $blockSetting) {
            if (! isset($blockSetting['label'])) {
                continue;
            }
            $type = $blockSetting['type'] ?? 'text';

            $setting = [
                'type' => $type,
                'name' => $name,
                'label' => $blockSetting['label'],
                'default-value' => $blockSetting['value'] ?? '',
                'placeholder' => $blockSetting['placeholder'] ?? '',
            ];

            if ($type === 'select') {
                $setting['options'] = $blockSetting['options'] ?? [];
            } elseif ($type === 'yes_no') {
                $setting['type'] = 'select';
                $setting['options'] = [
                    ['id' => 0, 'name' => phpb_trans('pagebuilder.no')],
                    ['id' => 1, 'name' => phpb_trans('pagebuilder.yes')]
                ];
            }

            $settings[] = $setting;
        }

        return $settings;
    }
}
