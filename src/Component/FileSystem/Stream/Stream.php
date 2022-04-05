<?php
namespace Laventure\Component\FileSystem\Stream;



/**
 * @Stream
*/
class Stream implements StreamInterface
{

    /**
     * @var string
    */
    protected $path;



    /**
     * Stream constructor
     *
     * @param string|null $path
    */
    public function __construct(string $path = null)
    {
          if ($path) {
              $this->setPath($path);
          }
    }




    /**
     * @inheritDoc
    */
    public function setPath($path)
    {
        $this->path = $path;
    }




    /**
     * @param string $mode
     * @return mixed
    */
    public function open(string $mode): bool
    {
         return fopen($this->path, $mode);
    }




    /**
     * @param $stream
     * @return void
    */
    public function close($stream)
    {
         fclose($stream);
    }




    /**
     * @inheritDoc
    */
    public function write($data, string $mode = 'a')
    {
        $stream = $this->open($mode);

        fwrite($stream, $data);

        $this->close($stream);
    }




    /**
     * @inheritDoc
    */
    public function read(int $length, string $mode = 'r')
    {
        $stream = $this->open($mode);

        fread($stream, $length);

        $this->close($stream);
    }
}