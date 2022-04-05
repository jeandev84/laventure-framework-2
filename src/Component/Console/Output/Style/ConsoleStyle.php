<?php
namespace Laventure\Component\Console\Output\Style;


/**
 * @ConsoleStyle
*/
class ConsoleStyle
{

      use ConsoleStyleTrait;



      /**
       * @param $text
       * @return string
      */
      public function foregroundGreen($text): string
      {
            return $this->styleForeground($text, 'green');
      }
}