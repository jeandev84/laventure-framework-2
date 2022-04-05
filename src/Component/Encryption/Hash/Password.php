<?php
namespace Laventure\Component\Encryption\Hash;


/**
 * @Password
*/
class Password
{

    /**
     * @param string $plainPassword
     * @param $algo
     * @param array $options
     * @return false|string|null
     */
    public function hash(string $plainPassword, $algo, array $options = [])
    {
        return password_hash($plainPassword, $algo, $options);
    }




    /**
     * Generate default password
     *
     * @param string $plainPassword
     * @return false|string|null
    */
    public function default(string $plainPassword)
    {
        return $this->hash($plainPassword, PASSWORD_DEFAULT);
    }



    /**
     * Hash password bcrypt
     *
     * @param string $plainPassword
     * @return false|string|null
    */
    public function bcrypt(string $plainPassword)
    {
        return $this->hash($plainPassword, PASSWORD_BCRYPT);
    }
}