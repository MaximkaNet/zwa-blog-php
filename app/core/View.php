<?php

namespace app\core;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class View
{
    private array $props = [];
    private string $template;

    public function __construct(string $template = "index")
    {
        $this->template = $template;
    }

    public function setContext(array $values): void
    {
        $this->props = $values;
    }

    public function addValuesToContext(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->props[$key] = $value;
        }
    }

    public function render(): string
    {
        $loader = __DIR__ . "/../../views";
        $m = new Mustache_Engine([
            "loader" => new Mustache_Loader_FilesystemLoader($loader)
        ]);
        return $m->render($this->template, $this->props);
    }
}