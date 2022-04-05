<?php
namespace Laventure\Contract\Debug;


use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Throwable;


/**
 * @ExceptionHandler
*/
interface ExceptionHandler
{

      /**
       * @param Throwable $e
       * @return mixed
      */
      public function report(Throwable $e);




      /**
       * @param Request $request
       * @param Throwable $e
       * @return Response
      */
      public function renderException(Request $request, Throwable $e): Response;





      /**
       * @param $output
       * @param Throwable $e
       * @return mixed
      */
      public function renderConsoleException($output, Throwable $e);
}