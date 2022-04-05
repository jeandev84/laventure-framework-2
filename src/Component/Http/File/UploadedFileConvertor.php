<?php
namespace Laventure\Component\Http\File;


/**
 * @UploadedFileConvertor
*/
trait UploadedFileConvertor
{

    /**
     * @param array $files
     * @return array
    */
    public function convertFiles(array $files): array
    {
        $resolvedFiles = $this->transformInformationFiles($files);

        $uploadedFiles = [];

        foreach ($resolvedFiles as $name => $items) {
            foreach ($items as $file) {
                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    return [];
                }

                $uploadedFiles[$name][] = new UploadedFile(
                    $file['name'],
                    $file['type'],
                    $file['tmp_name'],
                    $file['error'],
                    $file['size']
                );
            }
        }

        return $uploadedFiles;
    }



    /**
     * @param array $files
     * @return array
    */
    public function transformInformationFiles(array $files): array
    {
        $fileItems = [];

        foreach ($files as $name => $fileArray) {
            if (is_array($fileArray['name'])) {
                foreach ($fileArray as $attribute => $list) {
                    foreach ($list as $index => $value) {
                        $fileItems[$name][$index][$attribute] = $value;
                    }
                }
            }else{
                $fileItems[$name][] = $fileArray;
            }
        }

        return $fileItems;
    }
}