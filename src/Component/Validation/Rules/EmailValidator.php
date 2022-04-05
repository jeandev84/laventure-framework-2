<?php
namespace Laventure\Component\Validation\Rules;


use Laventure\Component\Validation\Contract\ValidatorInterface;



/**
 * @EmailValidator
*/
class EmailValidator implements ValidatorInterface
{


    /**
     * @var mixed
    */
    protected $value;




    /**
     * @param $value
    */
    public function __construct($value)
    {
         $this->value = $value;
    }




    /**
     * @return mixed
    */
    public function getValue()
    {
        return $this->value;
    }



    /**
     * @return bool
    */
    public function validate(): bool
    {
        // todo some logic for validate email
        return filter_var($this->getValue(), FILTER_VALIDATE_EMAIL);
    }




    /**
     * @return string
    */
    public function getMessage()
    {
        return "Email {$this->value} is not valid.";
    }
}