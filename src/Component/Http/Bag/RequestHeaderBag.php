<?php
namespace Laventure\Component\Http\Bag;


/**
 * @RequestHeaderBag
*/
class RequestHeaderBag extends HeaderBag
{

      /**
       * @return string
      */
      public function getContentType(): string
      {
           return $this->get('CONTENT_TYPE', '');
      }



      /**
       * @return bool
      */
      public function hasContentFormUrlEncoded(): bool
      {
           return stripos($this->getContentType(), 'application/x-www-form-urlencoded') === 0;
      }
}