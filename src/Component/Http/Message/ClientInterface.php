<?php
namespace Laventure\Component\Http\Message;


use Laventure\Component\Http\Client\ClientExceptionInterface;


/**
 * @ClientInterface
*/
interface ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ClientExceptionInterface If an error happens while processing the request.
    */
    public function sendRequest(RequestInterface $request): ResponseInterface;
}