<?php
namespace Laventure\Component\Auth;


/**
 * @AuthenticationInterface
*/
interface AuthenticationInterface
{

    /**
     * @param array $credentials
     * @param boolean $rememberMe
     * @return mixed
    */
    public function attempt(array $credentials, bool $rememberMe = false);
}