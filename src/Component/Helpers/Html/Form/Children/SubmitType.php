<?php
namespace Laventure\Component\Helpers\Html\Form\Children;


use Laventure\Component\Helpers\Html\Form\Types\InputType;


/**
 * @SubmitType
*/
class SubmitType extends InputType
{


    /**
     * @return string
    */
    public function getType(): string
    {
         return "submit";
    }



    /**
     * @return bool
    */
    public function isSubmit(): bool
    {
        return true;
    }




    /**
     * @return string
    */
    public function getValue(): string
    {
        return "Submit";
    }



    /**
     * @return string
    */
    public function renderLabel(): string
    {
         return false;
    }




    /**
     * @return array
    */
    protected function getAttributes(): array
    {
        $attributes = parent::getAttributes();

        unset($attributes['required']);

        return $attributes;
    }
}