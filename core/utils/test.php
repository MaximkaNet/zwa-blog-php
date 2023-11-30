<?php

namespace test;

function printLine(string $value, string $prefix = null): void
{
    $str = $prefix ?? "";
    $str .= $value . "\n";
    echo $str;
}

class Test {
    private array $tests;

    /**
     * Add new test
     * @param string $title The title of test
     * @param callable $callback The callback function with no arguments
     * @return void
     */
    public function addTest(string $title, callable $callback): void
    {
        $this->tests[] = ["title" => $title, "callback" => $callback];
    }

    /**
     * Start test loop
     * @return void
     */
    public function start(): void
    {
        $passed = 0;
        $with_error = 0;
        for ($i = 0; $i < count($this->tests); $i++) {
            printLine("Test ". $i + 1 . ": " . $this->tests[$i]["title"]);
            try {
                $result = $this->tests[$i]["callback"]();
                printLine("Result test " . $i + 1 .":");
                if(!$result)
                    printLine("Without result");
                else
                    printLine($result);
                $passed++;
            } catch (\Exception $exception){
                printLine("Test error: " . $exception);
                $with_error++;
            }
            printLine("========");
        }
        printLine("Passed: $passed, with error: $with_error, total: " . count($this->tests));
    }
}