<?php
namespace Laventure\Component\Authentication\Encrypt\Hash;



use Laventure\Component\Authentication\Database\UserInterface;

/**
 * @PasswordHash
*/
class PasswordHash
{

       /**
        * @var mixed
       */
       protected $algo;




       /**
        * @param $algo
       */
       public function __construct($algo)
       {
             $this->algo = $algo;
       }



       /**
        * @param UserInterface $user
        * @param string $plainPassword
        * @return false|string|null
       */
       public function hash(UserInterface $user, string $plainPassword)
       {
             return password_hash($plainPassword, $this->algo);
       }




       /**
        * @param UserInterface $user
        * @param $plainPassword
        * @return bool
       */
       public function verify(UserInterface $user, $plainPassword): bool
       {
            return password_verify($plainPassword, $user->getPassword());
       }
}