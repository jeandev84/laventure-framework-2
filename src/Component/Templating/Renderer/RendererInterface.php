<?php
namespace Laventure\Component\Templating\Renderer;



/**
 * @RendererInterface
*/
interface RendererInterface
{

    /**
     * @param string $template
     * @param array $arguments
     * @return mixed
    */
    public function render(string $template, array $arguments = []);
}