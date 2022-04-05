<?php
namespace Laventure\Component\FileSystem\Stream;



/**
 * @StreamInterface
*/
interface StreamInterface
{

       /**
        * @param $path
       */
       public function setPath($path);





       /**
        * @param string $mode
        * @return mixed
       */
       public function open(string $mode);




       /**
        * @param $data
        * @param string $mode
        * @return mixed
       */
       public function write($data, string $mode = 'a');




       /**
        * @param int $length
        * @param string $mode
        * @return mixed
       */
       public function read(int $length, string $mode = 'r');





       /**
        * @param $stream
        * @return void
       */
       public function close($stream);
}