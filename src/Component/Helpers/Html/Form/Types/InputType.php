<?php
namespace Laventure\Component\Helpers\Html\Form\Types;



use Laventure\Component\Helpers\Html\Form\FormType;



/**
 * @InputType
*/
abstract class InputType extends FormType
{

    /**
     * @return string
    */
    public function renderHtml(): string
    {
        return $this->renderTag();
    }



    /**
     * @return array
    */
    protected function getDefaultAttributes(): array
    {
         $attributes = [
             'type'  => $this->getType(),
             'name'  => $this->getName(),
             'value' => $this->getValue()
         ];


        if ($this->isSubmit()) {
            unset($attributes['name']);
        }

        return $attributes;
    }




    /**
     * @return false
    */
    public function isSubmit(): bool
    {
         return false;
    }



    /**
     * @return string
    */
    public function getTagName(): string
    {
        return 'input';
    }



    /**
     * @return string
    */
    abstract public function getType(): string;
}