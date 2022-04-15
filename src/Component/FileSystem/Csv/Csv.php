<?php


abstract class CsvFile {

    const EXTENSION = 'csv';


    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if ($extension !== static::EXTENSION) {
            trigger_error("Invalid extension file {$filename}");
        }
    }
}


/**
 *
 */
class CsvReader extends CsvFile {

    public function read() {

        // read file .
    }
}



class CsvWriter extends CsvFile {

    /**
     * @param $content
     * @return void
     */
    public function write($content)
    {
        // write content CSV
    }
}