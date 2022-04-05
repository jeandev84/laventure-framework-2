<?php
namespace Laventure\Component\Console\Output\Style;


/**
 * @ConsoleStyleTrait
*/
trait ConsoleStyleTrait
{

     /**
      * @var string[]
     */
     protected $foregroundColors = [
         'black'=> '0;30',
         'dark_gray'=> '1;30',
         'blue'=> '0;34',
         'light_blue'=> '1;34',
         'green'=> '0;32',
         'light_green'=> '1;32',
         'cyan'=> '0;36',
         'light_cyan'=> '1;36',
         'red'=> '0;31',
         'light_red'=> '1;31',
         'purple'=> '0;35',
         'light_purple'=> '1;35',
         'brown'=> '0;33',
         'yellow'=> '1;33',
         'light_gray'=> '0;37',
         'white'=> '1;37',
     ];



    /**
     * @var array
    */
    protected $backgroundColors = [
        'black'   => '40',
        'red'     => '41',
        'green'   => '42',
        'yellow'  => '43',
        'blue'    => '44',
        'magenta' => '45',
        'cyan'    => '46',
        'light_gray' => '47'
    ];




    /**
     * @return array
    */
    public function foregrounds(): array
    {
        return array_keys($this->foregroundColors);
    }






    /**
     * @param $name
     * @return bool
    */
    public function hasForeground($name): bool
    {
         return isset($this->foregroundColors[$name]);
    }



    /**
     * @return array
    */
    public function backgrounds(): array
    {
        return array_keys($this->backgroundColors);
    }




    /**
     * @param $name
     * @return bool
    */
    public function hasBackground($name): bool
    {
        return isset($this->backgroundColors[$name]);
    }




    /**
     * @param $text
     * @param $foregroundKey
     * @param $backgroundKey
     * @return string
    */
    public function style($text, $foregroundKey = null, $backgroundKey = null): string
    {
           $colored = "";

           if ($foregroundKey) {
               $colored .= $this->styleForeground($text, $foregroundKey);
           }

           if ($backgroundKey) {
               $colored .= $this->styleBackground($text, $backgroundKey);
           }

           return $colored;
    }




    /**
     * @param string $text
     * @param string $foregroundKey
     * @return string
    */
    public function styleForeground(string $text, string $foregroundKey): string
    {
        if (! $this->hasForeground($foregroundKey)) {
             return "";
        }

        return "\033[". $this->foregroundColors[$foregroundKey] . "m{$text}\033[0m";
    }



    /**
     * @param  string $text
     * @param  string $backgroundKey
     * @return string
    */
    public function styleBackground(string $text, string $backgroundKey): string
    {
        if (! $this->hasBackground($backgroundKey)) {
             return "";
        }

        return "\033[". $this->backgroundColors[$backgroundKey] . "m{$text}\033[0m";
    }
}