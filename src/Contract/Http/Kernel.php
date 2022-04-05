<?php
namespace Laventure\Contract\Http;


use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;



/**
 * @Kernel
*/
interface Kernel
{

    /**
     * @param Request $request
     * @return Response
    */
    public function handle(Request $request): Response;


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
    */
    public function terminate(Request $request, Response $response);
}