<?php
namespace Laventure\Component\Validation;

use Laventure\Component\Validation\Contract\ValidatorInterface;



/**
 * @Validation
*/
class Validation
{

       /**
        * Local language
        *
        * @var string
       */
       protected $lang;



       /**
         * Options params
         *
         * @var array
       */
       protected $options = [];




       /**
        * Storage validation rules
        *
        * @var ValidatorInterface[]
       */
       protected $rules = [];




       /**
        * Storage error messages
        *
        * @var string[]
       */
       protected $errors = [];



       /**
        * @param string $lang
        * @param array $options
       */
       public function __construct(string $lang = 'en_EN', array $options = [])
       {
             $this->lang    = $lang;
             $this->options = $options;
       }





       /**
        * Add rule
        *
        * @param ValidatorInterface $rule
        * @return $this
      */
      public function addRule(ValidatorInterface $rule): self
      {
          $this->rules[] = $rule;

          return $this;
      }




      /**
       * Add error
       *
       * @param $message
       * @return $this
      */
      public function addError($message): self
      {
          $this->errors[] = $this->translationMessage($message);

          return $this;
      }




      /**
       * Validate rules
       *
       * @return bool
      */
      public function validate(): bool
      {
           foreach ($this->rules as $rule) {

                $valid = $rule->validate();

                if (! $valid) {
                   $this->addError($rule->getMessage());
                   return false;
                }
           }

           return true;
      }




      /**
       * Get all error messages
       *
       * @return string[]
      */
      public function getErrors(): array
      {
          return $this->errors;
      }




      /**
       * @param string $message
       * @return string
      */
      protected function translationMessage(string $message): string
      {
            /*    $path = __DIR__."/{$this->lang}"; */

            $dirLang = explode('_', $this->lang, 2)[0];
            $path = __DIR__."/{$dirLang}";

            if (is_file($path)) {
                 $params = require $path;
                 return str_replace(array_keys($params), array_values($params), $message);
            }

            return $message;
      }
}