<?php

namespace app\core\document;

class Body extends DocumentElement
{
    /**
     * Initialize body
     * @param string $content
     */
    public function __construct(
        string $content = ""
    ) {
        parent::__construct("body");
        $this->inner_text = $content;
    }
}