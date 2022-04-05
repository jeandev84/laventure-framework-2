<?php
namespace Laventure\Component\Http\Response;


/**
 * @RedirectResponse
*/
class RedirectResponse extends Response
{

    /**
     * @var string
    */
    protected $path;




    /**
     * @param string $path
     * @param int $statusCode
     * @param array $headers
    */
    public function __construct(string $path, int $statusCode = 301, array $headers = [])
    {
          parent::__construct(null, $statusCode, $headers);
          $this->path = $path;
    }


    /**
     * @param string $path
     * @return $this
    */
    public function path(string $path): RedirectResponse
    {
        $this->path = $path;

        return $this;
    }





    /**
     * Send headers redirect
     * @return self
    */
    public function send()
    {
        http_response_code($this->statusCode);
        header(sprintf('Location: %s', $this->path));
        $this->renderTemplateRedirect();
    }



    /**
     * @return void
    */
    protected function renderTemplateRedirect()
    {
         echo sprintf(
      "<!DOCTYPE html>
             <html>
                <head>
                   <meta charset='UTF-8'>
                   <title>Redirect %s</title>
                </head>
                <body>
                    <h1>Redirect temporary to page %s with status %s</h1>
                </body>
             </html>
             ", $this->statusCode, $this->path, $this->statusCode);
    }
}