<?php

namespace App;

class Logger
{

    private $file;

    public function __construct($filename)
    {
        $this->file = $filename;
    }

    public function putLog($insert)
    {
        file_put_contents($this->file, date("Y-m-d H:i:s") . " " . $insert . "\r\n", FILE_APPEND);
    }

    public function getLog()
    {
        return @file_get_contents($this->file);
    }
}
