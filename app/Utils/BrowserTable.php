<?php

namespace App\Utils;


class BrowserTable
{
    private array $headers;
    private array $rows;
    private bool $progressIsEnabled;
    private string $separator = '+';

    const COLUMN_WIDTH = 13;

    /**
     * @param $headers
     * @param $rows
     * @param false $progressIsEnabled
     */
    public function __construct($headers, $rows, bool $progressIsEnabled = true)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->progressIsEnabled = $progressIsEnabled;
    }

    /**
     * @return void
     */
    public function render(): void
    {
        if ($this->progressIsEnabled) {
            $this->addProgressColumn();
        }

        foreach(reset($this->rows) as $firstItem) {
            $this->separator .= str_repeat('-', self::COLUMN_WIDTH + 2) . '+';
        }

        echo $this->separator . PHP_EOL;
        echo $this->drawRow($this->headers) . PHP_EOL;
        echo $this->separator . PHP_EOL;

        foreach ($this->rows as $row) {
            echo $this->drawRow($row) . PHP_EOL;
            ob_flush();
            flush();
            usleep(100000);
        }

        echo $this->separator . PHP_EOL;
    }

    /**
     * @param $row
     * @return string
     */
    public function drawRow($row): string
    {
        $line = '|';
        foreach ($row as $cell) {
            $string = strlen($cell) > 10 ? substr($cell, 0, 10) . '...' : $cell;

            $line .= ' ' . str_pad($string, self::COLUMN_WIDTH) . ' |';
        }

        return $line;
    }

    /**
     * @return void
     */
    public function addProgressColumn(): void
    {
        array_unshift($this->headers, 'Progress');

        foreach ($this->rows as $index => $row) {
            $percent = round((($index + 1) / count($this->rows)) * 100);

            $this->rows[$index] =  [
                ...['progress' => $percent . '%'],
                ...$this->rows[$index]
            ];
        }
    }
}
