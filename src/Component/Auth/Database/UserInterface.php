<?php
namespace Laventure\Component\Authentication\Database;

/**
 * @UserInterface
*/
interface UserInterface
{

      public function getPassword();
      public function getUsername();
}