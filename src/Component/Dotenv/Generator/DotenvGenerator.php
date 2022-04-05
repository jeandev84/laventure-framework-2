<?php
namespace Laventure\Component\Dotenv\Generator;


/**
 * @DotenvGenerator
*/
class DotenvGenerator
{


    /**
     * @var string
    */
    protected $root;




    /**
     * DotenvGenerator
     *
     * @param $root
    */
    public function __construct($root)
    {
         $this->root = $root;
    }



    /**
     * Generate dotenv file form stub
     *
     * @return false
     */
    public function generate(): bool
    {
        $content = file_get_contents(realpath(__DIR__ . '/stub/env.stub'));
        $filename = $this->root. DIRECTORY_SEPARATOR . '.env';

        if(! touch($filename)) {
            return false;
        }

        if (! file_put_contents($filename, $content)) {
            return false;
        }

        return true;
    }
}