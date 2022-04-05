<?php
namespace Laventure\Component\Validation\Contract;


/**
 * @ValidationRuleInterface
*/
interface ValidatorInterface
{
      public function getValue();
      public function validate(): bool;
      public function getMessage();
}