<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */


namespace Bip\Logger;


class Logger
{
    private string $filePath;
    private array $logArr = [];
    private int $logId;
    private int $maxLogCount = 100;

    /**
     * Logger constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (is_file($filePath)) {
            $this->logArr = explode(str_repeat('-', 80) . "\n", file_get_contents($filePath));
            // remove first member if it is empty
            if ($this->logArr[0] == '')
                array_shift($this->logArr);

            preg_match('#\[([0-9]+)\]#', end($this->logArr), $out);
            $this->logId = $out[1] ?? 0;
        } else
            $this->logId = 0;


    }

    /**
     * push a new log.
     * @param string $message
     * @param string $type
     */
    public function push(string $message, string $type = 'info')
    {
        $message = "[" . ++$this->logId . '] [' . date('Y-m-d H:i:s') . '] [' . $type . "]\n\t" . str_replace("\n", "\n\t", $message) . "\n\n";
        $this->logArr [] = $message;
    }

    /**3
     * set Maximum count for logs .
     * default is 100.
     * @param int $maxLogCount
     */
    public function setMaxLogCount(int $maxLogCount): void
    {
        $this->maxLogCount = $maxLogCount;
    }

    /**
     * Logger destructor.
     */
    public function __destruct()
    {
        if (count($this->logArr) > $this->maxLogCount)
            $offset = count($this->logArr) - $this->maxLogCount;
        else
            $offset = 0;

        file_put_contents($this->filePath, implode(str_repeat('-', 80) . "\n", array_slice($this->logArr, $offset)));

    }

}
