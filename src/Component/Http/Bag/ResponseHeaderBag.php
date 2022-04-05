<?php
namespace Laventure\Component\Http\Bag;


/**
 * @ResponseHeaderBag
*/
class ResponseHeaderBag extends HeaderBag
{

     /**
      * @return void
     */
     public function sendHeaders(int $statusCode)
     {
         http_response_code($statusCode);

         foreach ($this->params as $key => $value) {
             header(\is_numeric($key) ? $value : $key .' : ' . $value);
         }
     }



     /**
      * @return void
     */
     public function sendStatusMessage($statusCode): self
     {
         http_response_code($statusCode);

         return $this;
     }



    /**
     * @return void
    */
    protected function clearPreviousHeaders()
    {
        if (! headers_sent()) {
            header_remove();
        }
    }
}