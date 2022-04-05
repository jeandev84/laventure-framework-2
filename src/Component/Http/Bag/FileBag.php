<?php
namespace Laventure\Component\Http\Bag;


use Laventure\Component\Http\File\UploadedFile;
use Laventure\Component\Http\File\UploadedFileConvertor;

;

/**
 * @FileBag
*/
class FileBag extends ParameterBag
{

    use UploadedFileConvertor;


    /**
     * FileBag constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
         parent::__construct($params);

         $this->replace($params);
    }



    /**
     * @param array $files
     */
    public function replace(array $files = [])
    {
        $this->params = [];
        $this->add($files);
    }



    /**
     * @param array $files
     */
    public function add(array $files = [])
    {
        $files = $this->convertFiles($files);

        foreach ($files as $key => $file) {
            $this->set($key, $file);
        }
    }



    /**
     * @param string $key
     * @param $value
     * @return ParameterBag
     */
    public function set(string $key, $value): ParameterBag
    {
        if (!\is_array($value) && ! $value instanceof UploadedFile) {
            throw new \InvalidArgumentException(
                'An uploaded file must be an array or an instance of UploadedFile.'
            );
        }

        return parent::set($key, $value);
    }
}