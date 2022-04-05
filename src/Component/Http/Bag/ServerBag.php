<?php
namespace Laventure\Component\Http\Bag;


/**
 * @ServerBag
*/
class ServerBag extends ParameterBag
{


    /**
     * @return array
    */
    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->params as $key => $value) {
            if(strpos($key, 'HTTP_') === 0) {
                $headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers[$key] = $value;
            }
        }


        return $headers;
    }



    /**
     * @return mixed|null
    */
    public function getRequestUri()
    {
        return $this->get('REQUEST_URI');
    }




    /**
     * @return mixed
    */
    public function getDocumentRoot()
    {
         return $this->get('DOCUMENT_ROOT');
    }




    /**
     * @return mixed|null
    */
    public function getProtocol()
    {
        return $this->get('SERVER_PROTOCOL');
    }





    /**
     * @return string
    */
    public function getPathInfo(): string
    {
        return $this->get('PATH_INFO', '');
    }




    /**
     * @return mixed|null
    */
    public function getQueryString()
    {
        return $this->get('REQUEST_QUERY');
    }






    /**
     * @return mixed|null
    */
    public function getHost()
    {
        return $this->get('HTTP_HOST');
    }




    /**
     * @return mixed|null
    */
    public function getPort()
    {
        return  $this->get('SERVER_PORT');
    }




    /**
     * @return mixed|null
    */
    public function getMethod()
    {
        return $this->get('REQUEST_METHOD', '');
    }




    /**
     * @return void
    */
    public function getMethodOrDefault()
    {
        return $this->get('REQUEST_METHOD', 'GET');
    }




    /**
     * @return mixed|null
    */
    public function getUsername()
    {
        return $this->get('PHP_AUTH_USER');
    }





    /**
     * @return mixed|null
    */
    public function getPassword()
    {
        return $this->get('PHP_AUTH_PW');
    }





    /**
     * @return string
    */
    public function getScheme(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }





    /**
     * @return bool
    */
    public function isXhr(): bool
    {
        return $this->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }






    /**
     * Determine if the protocol is secure
     *
     * @return bool
    */
    public function isSecure(): bool
    {
        $https = $this->get('HTTPS');
        $port  = $this->get('SERVER_PORT');


        return $https == 'on' && $port == 443;
    }


    /**
     * @return string
    */
    public function getBaseURL(): string
    {
         $user = $this->get('PHP_AUTH_USER', '');
         $pwd  = $this->get('PHP_AUTH_PW');

         $pass = $pwd ?  ':' . $pwd : '';

         if ($user || $pwd) {
             $pass = '@'. $pwd;
         }

         return sprintf('%s://%s%s%s', $this->getScheme(), $user, $pass, $this->getHost());
    }




    /**
     * @return string
    */
    public function getURL(): string
    {
         return $this->getBaseURL() . $this->getRequestUri();
    }




    /**
     * @param $method
     * @return void
    */
    public function setMethod($method)
    {
         $this->set('REQUEST_METHOD', $method);
    }
}