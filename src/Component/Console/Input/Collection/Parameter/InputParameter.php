<?php
namespace Laventure\Component\Console\Input\Collection\Parameter;



use Laventure\Component\Console\Input\Collection\Parameter\Contract\InputParameterInterface;
use Laventure\Component\Console\Input\Collection\Parameter\Contract\InputStateInterface;


/**
 * @InputParameter
*/
abstract class InputParameter implements InputParameterInterface, InputStateInterface
{

    /**
     * @var string
     */
    protected $name;



    /**
     * @var int|void
     */
    protected $mode;



    /**
     * @var string
    */
    protected $description;



    /**
     * @var string
    */
    protected $default;




    /**
     * InputParameter constructor
     *
     * @param string $name
     * @param int|null $mode
     * @param string|null $description
     * @param string|null $default
    */
    public function __construct(string $name, int $mode = null, string $description = null, string $default = null)
    {
        $this->name($name)
            ->mode($mode)
            ->description($description)
            ->default($default)
        ;
    }



    /**
     * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * @return string
    */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * @return int|void
    */
    public function getMode()
    {
        return $this->mode;
    }


    /**
     * @return string
    */
    public function getDefault(): string
    {
        return $this->default;
    }



    /**
     * @param $name
     * @return $this
    */
    public function name($name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @param $description
     * @return $this
    */
    public function description($description): self
    {
        $this->description = $description;

        return $this;
    }



    /**
     * @param $default
     * @return $this
    */
    public function default($default): self
    {
         $this->default = $default;

         return $this;
    }



    /**
     * @param $mode
     * @return $this
    */
    public function mode($mode): self
    {
         return $this->withMode($mode);
    }



    /**
     * @param int $mode
     * @return $this
    */
    public function withMode($mode): self
    {
        $this->mode = (int) $mode;

        return $this;
    }




    /**
     * @return bool
    */
    public function isOptional(): bool
    {
        return $this->mode === self::OPTIONAL;
    }



    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->mode === self::REQUIRED;
    }



    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->mode === self::IS_ARRAY;
    }

}