<?php

namespace PHPageBuilder\Modules\GrapesJS\Block;

use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\ThemeBlock;

class BaseModel
{
    /**
     * @var ThemeBlock $block
     */
    protected $block;

    /**
     * @var array $data
     */
    protected $data;

    /**
     * @var PageContract $page
     */
    protected $page;

    /**
     * @var bool $forPageBuilder
     */
    protected $forPageBuilder;

    /**
     * BaseModel constructor.
     *
     * @param ThemeBlock $block
     * @param array $data
     * @param PageContract|null $page
     * @param bool $forPageBuilder
     */
    public function __construct(ThemeBlock $block, $data = [], PageContract $page = null, $forPageBuilder = false)
    {
        $this->block = $block;
        $this->data = is_array($data) ? $data : [];
        $this->page = $page;
        $this->forPageBuilder = $forPageBuilder;

        if (phpb_in_editmode() && method_exists($this, 'initEdit')) {
            $this->initEdit();
        } else {
            $this->init();
        }
    }

    /**
     * Initialize the model.
     */
    protected function init()
    {
    }

    /**
     * Return the given setting stored for this block instance using the page builder.
     *
     * @param $setting
     * @param bool $allowHtml
     * @return string
     */
    public function setting($setting, $allowHtml = false)
    {
        $value = $this->block->get('settings.' . $setting . '.value');

        if (isset($this->data['settings']['attributes'][$setting])) {
            $value = $this->data['settings']['attributes'][$setting];
        }

        return $allowHtml ? $value : phpb_e($value);
    }

    /**
     * Return data of this block, passed as argument by a parent block.
     *
     * @param $key
     * @return string
     */
    public function data($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Return data of the child block with the given relative ID.
     *
     * @param $childBlockId
     * @return string
     */
    public function childData($childBlockId)
    {
        return $this->data['blocks'][$childBlockId] ?? null;
    }

}
