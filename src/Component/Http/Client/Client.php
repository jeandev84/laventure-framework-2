<?php
namespace Laventure\Component\Http\Client;

use Laventure\Component\Http\Message\ClientInterface;
use Laventure\Component\Http\Message\RequestInterface;
use Laventure\Component\Http\Message\ResponseInterface;

/**
 * @Client
*/
class Client implements ClientInterface
{

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // TODO: Implement sendRequest() method.
    }
}