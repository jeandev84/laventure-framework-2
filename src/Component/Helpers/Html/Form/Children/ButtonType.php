<?php
namespace Laventure\Component\Helpers\Html\Form\Children;


use Laventure\Component\Helpers\Html\Form\FormType;


/**
 * @ButtonType
*/
class ButtonType extends FormType
{


    /**
     * @return void
    */
    public function renderHtml()
    {
         $this->renderTag(true);
    }




    /**
     * @return string
    */
    public function getValue(): string
    {
        return $this->options['value'] ?? 'Submit';
    }




    /**
     * @return string
    */
    public function getTagName(): string
    {
         return 'button';
    }

    protected function getDefaultAttributes(): array
    {
          return [];
    }
}