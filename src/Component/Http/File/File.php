<?php
namespace Laventure\Component\Http\File;


/**
 * @File
*/
class File
{

    /**
     * @var string
     */
    protected $originalName;



    /**
     * @var string
     */
    protected $mimeType;



    /**
     * @var string
     */
    protected $tempFile;



    /**
     * @var int
     */
    protected $error;




    /**
     * @var int
     */
    protected $size;





    /**
     * UploadedFile constructor.
     * @param string $originalName
     * @param string $mimeType
     * @param string $tempFile
     * @param string $error
     * @param int $size
     */
    public function __construct(string $originalName, string $mimeType, string $tempFile, string $error, int $size)
    {
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
        $this->tempFile = $tempFile;
        $this->error = $error;
        $this->size = $size;
    }



    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }



    /**
     * @param string $originalName
     * @return File
     */
    public function setOriginalName(string $originalName): File
    {
        $this->originalName = $originalName;

        return $this;
    }



    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }



    /**
     * @param string $mimeType
     * @return File
     */
    public function setMimeType(string $mimeType): File
    {
        $this->mimeType = $mimeType;

        return $this;
    }




    /**
     * @return string
     */
    public function getTempFile(): string
    {
        return $this->tempFile;
    }



    /**
     * @param string $tempFile
     * @return File
     */
    public function setTempFile(string $tempFile): File
    {
        $this->tempFile = $tempFile;

        return $this;
    }


    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }



    /**
     * @param int $error
     * @return File
     */
    public function setError(int $error): File
    {
        $this->error = $error;

        return $this;
    }




    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }



    /**
     * @param int $size
     * @return File
     */
    public function setSize(int $size): File
    {
        $this->size = $size;

        return $this;
    }


    /**
     * @return mixed|string
    */
    public function getClientExtension()
    {
        return pathinfo($this->originalName)['extension'] ?? '';
    }

}