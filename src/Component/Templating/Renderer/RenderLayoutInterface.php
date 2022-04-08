<?php
namespace Laventure\Component\Templating\Renderer;

/**
 * @RenderLayoutInterface
*/
interface RenderLayoutInterface
{

     /**
      * Set current layout.
      *
      * @param $layout
      * @return mixed
     */
     public function withLayout($layout);



     /**
      * Render content layout
      *
      * $content is the content of template
      * @param $content
      * @return mixed
     */
     public function renderLayout($content);
}