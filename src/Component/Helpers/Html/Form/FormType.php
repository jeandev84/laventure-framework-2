<?php
namespace Laventure\Component\Helpers\Html\Form;


use Laventure\Component\Helpers\Html\Html;


/**
 * @FormChildren
*/
abstract class FormType extends Html
{

      /**
       * @var string
      */
      protected $name;


      /**
       * @var array
      */
      protected $data;



      /**
       * @var FormType
      */
      protected $parent;




      /**
       * @var FormType[]
      */
      protected $children = [];




      /**
       * @var array
      */
      protected $options = [
          "label"     =>  "",
          "labelAttr" =>  [],
          "required"  =>  true,
          "attr"      => []
      ];





      /**
       * @var string[]
      */
      protected $errors = [];




      /**
       * @param string $name
       * @param array $data
       * @param array $options
      */
      public function __construct(string $name, array $data, array $options = [])
      {
            $this->name    = $name;
            $this->data    = new FormValue($data);
            $this->addOptions($options);
      }




      /**
       * @return string
      */
      public function getName(): string
      {
          return $this->name;
      }




      /**
       * @return array
      */
      public function getData(): array
      {
          return $this->data->all();
      }




      /**
       * @return mixed|null
      */
      public function getValue()
      {
           return $this->data->get($this->name);
      }



      /**
       * @param $errors
       * @return void
      */
      public function setErrors($errors)
      {
           $this->errors = (array) $errors;
      }



      /**
       * @return string[]
      */
      public function getErrors(): array
      {
           return $this->errors;
      }




      /**
       * @param array $options
       * @return void
      */
      public function addOptions(array $options)
      {
           $this->options = array_merge($this->options, $options);
      }





      /**
       * Parent getChildren(), Child getParent()
       *
       * @param FormType $parent
       * @return void
      */
      public function setParent(FormType $parent)
      {
           $this->parent = $parent;
      }




      /**
       * Get form parent
       *
       * @return $this
      */
      public function getParent(): self
      {
           return $this->parent;
      }



      /**
       * @param FormType $children
       * @return $this
      */
      public function addChildren(FormType $children): self
      {
           $this->children[] = $children;

           return $this;
      }




      /**
       * @return FormType[]
      */
      public function getChildren(): array
      {
          return $this->children;
      }



      /**
       * @param string $name
       * @param $default
       * @return mixed|null
      */
      public function getOption(string $name, $default = null)
      {
          return $this->options[$name] ?? $default;
      }


      /**
       * @return array
      */
      protected function getAttributes(): array
      {
          $attributes = array_merge($this->getDefaultAttributes(), $this->options['attr'] ?? []);

          if ($this->getOption('required') === true) {
              $attributes['required'] = 'required';
          }


          return $attributes;
      }




      /**
        * @param bool $close
        * @return string
      */
      public function renderTag(bool $close = false): string
      {
           $attributes = $this->getAttributes();

           $template  = $this->renderLabel();
           $template .= $this->openTag($this->getTagName(), $attributes);

           if ($close) {
               $template .=  $this->getValue() . $this->closeTag($this->getTagName());
           }

           return $template;
      }




      /**
       * @return string
      */
      public function renderLabel(): string
      {
           $label = $this->getOption("label");

           if ($label === false) {
                return "";
           }

           $labelName = $label ??  ucfirst($this->name);

           $attributes = array_merge(["for" => $this->getName()], $this->getOption("labelAttr", []));

           return $this->doubleTag('label', $attributes, $labelName);
      }



      /**
       * @return void
      */
      public function renderHtmlWithErrors()
      {

      }



      /**
       * @return mixed
      */
      abstract public function renderHtml();



      /**
       * @return mixed
      */
      abstract public function getTagName();



     /**
      * @return array
     */
     abstract protected function getDefaultAttributes(): array;

}