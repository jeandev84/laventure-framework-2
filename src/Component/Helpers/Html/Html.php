<?php
namespace Laventure\Component\Helpers\Html;



/**
 * @HtmlTag
*/
class Html extends Tag
{

    /**
     * @param string $name
     * @param array $attributes
     * @return string
    */
    public function label(string $name, array $attributes = []): string
    {
        return $this->doubleTag('label', $attributes, $name);
    }




    /**
     * @param string $href
     * @param string $link
     * @param array $attributes
     * @return string
    */
    public function a(string $href, string $link, array $attributes = []): string
    {
        $attributes = array_merge(compact('href'), $attributes);

        return $this->doubleTag('a', $attributes, $link);
    }
}