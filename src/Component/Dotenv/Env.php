<?php
namespace Laventure\Component\Dotenv;


/**
 * @Env
*/
class Env
{

    /**
     * @param string|null $environ
     */
    public function __construct(string $environ = null)
    {
        if ($environ) {
            $this->put($environ);
        }
    }


    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->put(sprintf('%s=%s', $key, $value));
    }




    /**
     * @param string $environ
    */
    public function put(string $environ)
    {
        if($environ = $this->validate($environ)) {

            list($key, $value) = explode("=", $environ, 2);

            $value = trim($value);

            if (stripos($value, '#') !== false) {
                $value = explode('#', $value, 2)[0];
            }

            putenv(sprintf('%s=%s', $key, $value));

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }



    /**
     * @param string $key
     * @return array|false|mixed|string
     */
    public function read(string $key)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return getenv($key);
    }




    /**
     * @param string $env
     * @return false|string
    */
    protected function validate(string $env)
    {
        if(preg_match('#^(?=[A-Z])(.*)=(.*)$#', $env, $matches)) {
            return str_replace(' ', '', trim($matches[0]));
        }

        return false;
    }
}