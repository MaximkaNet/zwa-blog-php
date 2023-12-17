<?php

namespace app\core\http;

class ResponseBody
{
    private array|null $errors;
    private array|null $data;
    private string|null $message;

    public function __construct(array $data = null, string $message = null, array $errors = [])
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->message = $message;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function toJSON(): string
    {
        $body = [
            "errors" => $this->errors,
            "data" => $this->data,
            "message" => $this->message
        ];
        return json_encode($body);
    }
}