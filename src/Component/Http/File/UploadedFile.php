<?php
namespace Laventure\Component\Http\File;


/**
 * @UploadedFile
*/
class UploadedFile extends File
{

    /**
     * @param string $originalName
     * @param string $mimeType
     * @param string $tempFile
     * @param string $error
     * @param int $size
    */
    public function __construct(
        string $originalName,
        string $mimeType,
        string $tempFile,
        string $error,
        int $size
    )
    {
         parent::__construct($originalName, $mimeType, $tempFile, $error, $size);
    }


    /**
     * @param string $target
     * @param string|null $newFilename
     * @return false|string|null
    */
    public function move(string $target, string $newFilename = null)
    {
        if($this->error != UPLOAD_ERR_OK) {
            return false;
        }


        if (! $newFilename) {
            $newFilename = $filename ?? sha1(mt_rand()) . '_' . sha1(mt_rand());
            $newFilename .= '.'. $this->getClientExtension();
        }

        if(! is_dir($target)) {
            mkdir($target, 0777, true);
        }

        $uploadedFilePath = rtrim($target, '/') . '/'. $newFilename;

        if ($this->moveTo($uploadedFilePath)) {
             return $newFilename;
        }

        return false;
    }


    /**
     * @param string $target
     * @return bool
    */
    public function moveTo(string $target): bool
    {
        return move_uploaded_file($this->tempFile, $target);
    }
}