<?php

namespace app\core\http;

use app\core\exception\RequestException;
use app\core\interfaces\IUploadedFile;
use app\core\utils\UUID;

class UploadedFile implements IUploadedFile
{
    private string $name;
    private string $tmp_path;
    private int $size;
    private string $type;
    private int $error;

    public function __construct(
        string $name,
        string $type,
        int $size,
        string $tmp_name,
        int $error
    )
    {
        preg_match("/(?<name>[a-zA-Z]+)\/(?<type>[a-zA-Z]+)/", $type, $matches);
        $this->error = $error;
        $this->name = UUID::v4();
        $this->size = $size;
        $this->type = strtolower($matches["type"] ?? "png");
        $this->tmp_path = $tmp_name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getTmpPath(): string
    {
        return $this->tmp_path;
    }

    /**
     * Move uploaded file
     * @param string $dist Destination upload
     * @return bool Returns TRUE if file has been moved, FALSE otherwise
     * @throws RequestException throws if uploaded file has error
     */
    public function moveTo(string $dist = "/static"): bool
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            throw new RequestException(
                match ($this->error) {
                    UPLOAD_ERR_EXTENSION => "Upload file extension",
                    UPLOAD_ERR_CANT_WRITE => "Uploaded file cant write",
                    UPLOAD_ERR_NO_TMP_DIR => "Uploaded file no tmp dir",
                    UPLOAD_ERR_PARTIAL => "Upload file partial",
                    UPLOAD_ERR_FORM_SIZE => "Upload file form size",
                    UPLOAD_ERR_INI_SIZE => "Upload file ini size",
                    UPLOAD_ERR_NO_FILE => "No file",
                    default => "Unknown error: ". $this->error
                },
                400 // Bad request
            );
        }

        $project_root = __DIR__ . "/../../..";
        $full_path = $project_root . $dist . "/" . $this->name . "." . $this->type;
        return move_uploaded_file($this->tmp_path, $full_path);
    }
}