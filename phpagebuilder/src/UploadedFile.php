<?php

namespace PHPageBuilder;

class UploadedFile
{
    /**
     * Return the URL of this uploaded file.
     */
    public function getUrl()
    {
        return phpb_full_url(phpb_config('general.uploads_url') . '/' . $this->public_id . '/' . $this->original_file);
    }
}
