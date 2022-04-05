<?php
namespace Laventure\Component\Helpers\Html\Form\Children;


use Laventure\Component\Helpers\Html\Form\FormType;



/**
 * @TextareaType
*/
class TextareaType extends FormType
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
    public function getTagName(): string
    {
         return "textarea";
    }

    protected function getDefaultAttributes(): array
    {
         return [];
    }
}