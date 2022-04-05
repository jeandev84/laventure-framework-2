<?php
namespace Laventure\Component\Helpers\Html\Form\Children;


use Laventure\Component\Helpers\Html\Form\Types\InputType;


/**
 * @EmailType
*/
class EmailType extends InputType
{

    public function getType(): string
    {
         return 'email';
    }
}