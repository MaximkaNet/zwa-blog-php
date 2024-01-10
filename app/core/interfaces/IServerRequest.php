<?php

namespace app\core\interfaces;

interface IServerRequest
{
    /**
     * Get server params
     * @return array
     */
    public function getServerParams(): array;

    /**
     * Get cookie params
     * @return array
     */
    public function getCookieParams(): array;

    /**
     * Get uploaded files
     * @return array
     */
    public function getUploadedFiles(): array;

    /**
     * Return request body
     * @return array|null
     */
    public function getParsedBody(): null|array;
}