<?php
namespace Laventure\Component\Helpers\Html\Form;



/**
 * @FormBuilder
*/
class FormBuilder
{


      /**
       * @var Form
      */
      protected $form;




      /**
       * @param array $parameters
      */
      public function __construct(array $parameters = [])
      {
            $this->form = $this->createForm([]);
      }




      /**
       * @param array $data
       * @return Form
      */
      public function createForm(array $data = []): Form
      {
           return new Form($data);
      }



      /**
        * @param string $name
        * @param string|null $type
        * @param array $options
        * @return Form
      */
      public function add(string $name, string $type = null, array $options = []): Form
      {
            return $this->form->add($name, $type, $options);
      }



      /**
       * @return Form
      */
      public function getForm(): Form
      {
          return $this->form;
      }
}