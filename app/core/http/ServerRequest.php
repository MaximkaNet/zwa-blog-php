<?php

namespace app\core\http;

use app\core\interfaces\IServerRequest;

class ServerRequest implements IServerRequest
{

    /**
     * Get uploaded files
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $this->parseFilesRecursive($_FILES);
    }

    private function parseFilesRecursive(array $subject = []): array
    {
        $result = [];
        foreach ($subject as $file => $params) {
            if (isset($params["name"])) {
                if (is_string($params["name"])) {
                    $uploaded_file = new UploadedFile(
                        $params["name"], // TODO: UUID
                        $params["type"],
                        $params["size"],
                        $params["tmp_name"],
                        $params["error"]
                    );
                    $result[$file] = $uploaded_file;
                } elseif (isset($params["name"][0])) {
                    $len = count($params["name"]);
                    for ($i = 0; $i < $len; $i++) {
                        $uploaded_file = new UploadedFile(
                            $params["name"][$i],
                            $params["type"][$i],
                            $params["size"][$i],
                            $params["tmp_name"][$i],
                            $params["error"][$i]
                        );
                        $result[$file][] = $uploaded_file;
                    }
                } else {
                    // Nested node
                    $result[$file]["name"] = $this->parseFilesRecursive($params["name"]);
                }
            } else {
                // Nested node
                $result[$file] = $this->parseFilesRecursive($params);
            }
        }
        return $result;
    }

    /**
     * Return request body
     * @return array|null
     */
    public function getParsedBody(): null|array
    {
        if(isset($_POST)) return $_POST;
        // Something ...
        return null;
    }

    /**
     * Get server params
     * @return array
     */
    public function getServerParams(): array
    {
        return [
            "REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"],
            "REQUEST_TIME" => $_SERVER["REQUEST_TIME"],
            "REQUEST_URI" => $_SERVER["REQUEST_URI"],
            "QUERY_STRING" => $_SERVER["QUERY_STRING"],
            "SERVER_PROTOCOL" => $_SERVER["SERVER_PROTOCOL"],
            "GATEWAY_INTERFACE" => $_SERVER["GATEWAY_INTERFACE"],
//            "CONTENT_LENGTH" => $_SERVER["CONTENT_LENGTH"],
//            "CONTENT_TYPE" => $_SERVER["CONTENT_TYPE"],
            "HTTP_USER_AGENT" => $_SERVER["HTTP_USER_AGENT"],
        ];
    }

    /**
     * Get cookie params
     * @return array
     */
    public function getCookieParams(): array
    {
        // TODO: Implement getCookieParams() method.
    }
}