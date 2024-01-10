<?php

namespace app\core\interfaces;

interface IUploadedFile
{
    const UPLOADED_IMAGE = "image/*";

    /**
     * Move uploaded file
     * @param string $dist Destination upload
     * @return bool
     */
    public function moveTo(string $dist): bool;
}