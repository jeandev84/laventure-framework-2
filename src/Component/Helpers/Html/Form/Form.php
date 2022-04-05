<?php
namespace Laventure\Component\Helpers\Html\Form;


use Laventure\Component\Helpers\Html\Form\Children\SubmitType;
use Laventure\Component\Helpers\Html\Form\Children\TextType;
use Laventure\Component\Helpers\Html\Html;



/**
 * @Form
*/
class Form extends Html
{


      /**
       * @var string
      */
      protected $tagName = 'form';



      /**
       * @var string
      */
      protected $surround = 'div';




      /**
       * @var array
      */
      protected $data = [];



      /**
       * @var FormType[]
      */
      protected $children = [];



      /**
       * @var array
      */
      protected $attributes = [];




      /**
        * @var array
      */
      protected $errors = [];



      /**
       * @var bool
      */
      protected $started = false;



      /**
       * @var bool
      */
      protected $closed = false;




      /**
        * @param array $data
      */
      public function __construct(array $data = [])
      {
            if ($data) {
                $this->setData($data);
            }
      }




      /**
       * @param array $data
       * @return void
      */
      public function setData(array $data)
      {
           $this->data = $data;
      }




      /**
       * @return FormValue
      */
      public function getData(): FormValue
      {
          return new FormValue($this->data);
      }






      /**
       * @param string $name
       * @return mixed|null
      */
      public function get(string $name)
      {
          return $this->getData()->get($name);
      }





      /**
        * @param array $attributes
        * @return $this
      */
      public function open(array $attributes): self
      {
            $this->attributes = $attributes;

            $this->started = true;

            return $this;
      }



      /**
       * @param string $name
       * @param string|null $type
       * @param array $options
       * @return $this
      */
      public function add(string $name, string $type = null, array $options = []): self
      {
           $type = $type ?? TextType::class;

           $options['errors'] = $this->errors[$name] ?? [];

           $children = new $type($name, $this->data, $options);

           if ($children instanceof FormType) {
               $this->children[$name]  = $children;
           }

           return $this;
      }




      /**
       * @param string|null $type
       * @param array $attributes
       * @return $this
      */
      public function submit(string $type = null, array $attributes = []): self
      {
           $this->closed = true;

           return $this->add('submit', $type ?? SubmitType::class, $attributes);
      }



      /**
       * @return void
      */
      public function close()
      {
           $this->closed = true;

           echo $this->__toString();
      }




      /**
       * @return bool
      */
      public function isSubmit(): bool
      {
           return ! empty($this->data);
      }



      /**
       * @return bool
      */
      public function isValid(): bool
      {
          return $this->isSubmit() && ! $this->hasErrors();
      }






      /**
       * @return string
      */
      public function __toString()
      {
           return $this->renderHtml();
      }




      /**
       * @return false|string
      */
      public function renderHtml()
      {
           if (! $this->children) {
                return false;
           }

           if ($this->isWrap()) {

               $html[] = $this->createOpenTag();
               $html[] = $this->renderChildren();
               $html[] = $this->closeTag($this->tagName);
               return PHP_EOL. join(PHP_EOL, $html);
           }

           return $this->renderChildren();
      }




      /**
       * @return array
      */
      protected function getChildrenTemplates(): array
      {
            $templates = [];

            foreach ($this->children as $child) {
                $templates[] = $this->surround($child->renderHtml());
            }

            return $templates;
      }



     /**
      * @param $content
      * @return string
     */
      public function surround($content): string
      {
           if (! $this->surround) {
               return $content;
           }


           return $this->doubleTag($this->surround, [], $content);
      }



      /**
       * @return string
      */
      public function renderChildren(): string
      {
           return join(PHP_EOL, $this->getChildrenTemplates());
      }




      /**
       * @param string $name
       * @return FormType
       */
      public function getRow(string $name): FormType
      {
          if (! isset($this->children[$name])) {
              trigger_error("unable form children '{$name}'");
          }

          return $this->children[$name];
      }




      /**
       * @param string $name
       * @param array $attributes
       * @return string
      */
      public function renderRow(string $name, array $attributes = []): string
      {
            $children = $this->getRow($name);

            if ($attributes) {
                $children->addOptions($attributes);
            }

            return $children->renderHtml();
      }





      /**
       * @param array $errors
       * @return void
      */
      public function setErrors(array $errors)
      {
            foreach ($this->children as $child) {
                 if (isset($errors[$child->getName()])) {
                      $child->setErrors($errors[$child->getName()]);
                 }
            }

            $this->errors = $errors;
      }





      /**
        * @return bool
      */
      public function hasErrors(): bool
      {
          return empty($this->errors);
      }




      /**
        * @return array
      */
      public function getErrors(): array
      {
          return array_values($this->errors);
      }






      /**
       * @return string
      */
      protected function createOpenTag(): string
      {
           return $this->openTag($this->tagName, $this->attributes);
      }




      /**
       * @return bool
      */
      protected function isOpened(): bool
      {
           return $this->started;
      }




      /**
       * @return bool
      */
      protected function isClosed(): bool
      {
          return $this->closed;
      }




      /**
       * @return bool
      */
      protected function isWrap(): bool
      {
          return $this->isOpened() && $this->isClosed();
      }
}