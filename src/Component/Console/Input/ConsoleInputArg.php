<?php
namespace Laventure\Component\Console\Input;


/**
 * @ConsoleInputArg
*/
class ConsoleInputArg extends InputArgv
{


    /**
     * ConsoleInputArg constructor.
     *
     * @param array $tokens
    */
    public function __construct(array $tokens = [])
    {
          if (! $tokens) {
               $tokens =  $_SERVER['argv'];
          }

          parent::__construct($tokens);
    }




    /**
     * @inheritDoc
    */
    public function count(): int
    {
        return $_SERVER['argc'] ?? 0;
    }



    /**
     * @inheritDoc
    */
    public function parseTokens(array $tokens)
    {
         foreach ($tokens as $token) {
              $this->processParseToken($token);
         }
    }



    /**
     * Process parsing token
     *
     * @param $token
     * @return void
    */
    private function processParseToken($token)
    {
        preg_match("/^(.+)=(.+)$/", $token, $matches);

        if (! empty($matches)) {

            if (preg_match('#^--([^=]+)=(.*)$#i', $token,$params)) {
                 $this->options[$params[1]] = $params[2];
            }else {
                list($tokenName, $tokenValue) = explode('=', $token, 2);
                $this->arguments[$tokenName] = $tokenValue;
            }

        } else {

            if (preg_match('#^--([^=]+)$#i', $token,$params)) {
                $this->flags[$params[1]] = true;
            } elseif (preg_match('#^-([^=]+)$#i', $token,$params)) {
                $this->flags[$params[1]] = true;
            }else {
                $this->arguments[] = $token;
            }
        }
    }
}