<?php

namespace app\helpers\validator;

class Result
{
    private string $message;
    private bool $valid;

    /**
     * Init result
     * @param bool $valid
     * @param string $message
     */
    public function __construct(
        bool $valid = false,
        string $message = ""
    )
    {
        $this->valid = $valid;
        $this->message = $message;
    }

    /**
     * Check if valid
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Check if not valid
     * @return bool
     */
    public function isNotValid(): bool
    {
        return !$this->valid;
    }

    /**
     * Get message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?? "";
    }
}