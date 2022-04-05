<?php
namespace Laventure\Component\Templating\Renderer;


/**
 * @RenderCompressor
*/
class RenderCompressor
{

    /**
     * @var string[]
    */
    protected $search =  [
        "/(\n)+/",
        "/\r\n+/",
        "/\n(\t)+/",
        "/\n(\ )+/",
        "/\>(\n)+</",
        "/\>\r\n</",
    ];



    /**
     * @var string[]
     */
    protected $replace = [
        "\n",
        "\n",
        "\n",
        "\n",
        '><',
        '><',
    ];



    /**
     * @param string $content
     * @return array|string|string[]|null
    */
    public function compress(string $content)
    {
        return preg_replace($this->search, $this->replace, $content);
    }
}