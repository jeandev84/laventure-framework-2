<?php
namespace Laventure\Component\Helpers\Html\Form\Children;



use Laventure\Component\Helpers\Html\Form\Types\InputType;


/**
 * @TextType
*/
class TextType extends InputType
{


    /**
     * @return string
    */
    public function getType(): string
    {
        return 'text';
    }


    /*
    public function renderHtml(): string
    {
        $html[] = '<p>'. parent::renderHtml() .'</p>';
        $html[] = "\n". join("\n", $this->errors);

        return join('', $html);

    }
    */
}