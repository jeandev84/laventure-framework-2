<?php
namespace Laventure\Component\Auth\Database;

/**
 * @UserInterface
*/
interface UserInterface
{

      public function getPassword();
      public function getUsername();
}