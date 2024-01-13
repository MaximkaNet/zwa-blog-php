<?php

namespace app\core\http;

class Response
{
    private array|null $errors;
    private array|null $data;
    private string|null $message;

    /**
     * Initialize response
     * @param array|null $data
     * @param string|null $message
     * @param array $errors
     */
    public function __construct(array $data = null, string $message = null, array $errors = [])
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->message = $message;
    }

    /**
     * Get errors
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * Get data
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set errors
     * @param array|null $errors
     * @return void
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Add error
     * @param string $message
     * @param int|null $code
     * @return void
     */
    public function addError(string $message, int|null $code = null): void
    {
        $error["message"] = $message;
        if(isset($code)){
            $error["code"] = $code;
        }
        $this->errors[] = $error;
    }

    /**
     * Set data to response
     * @param array|null $data
     * @return void
     */
    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    /**
     * Add data to response
     * @param mixed $data
     * @return void
     */
    public function addData(mixed $data): void
    {
        $this->data[] = $data;
    }

    /**
     * Set response message
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get response message
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Convert response to json
     * @return string
     */
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