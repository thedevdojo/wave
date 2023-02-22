<?php

namespace PHPageBuilder\Contracts;

interface PageRepositoryContract
{
    /**
     * Create a new page.
     *
     * @param array $data
     * @return bool|object
     */
    public function create(array $data);

    /**
     * Update the given page with the given updated data.
     *
     * @param $page
     * @param array $data
     * @return bool|object|null
     */
    public function update($page, array $data);
}
