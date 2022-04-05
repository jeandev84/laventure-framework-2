<?php
namespace Laventure\Component\Helpers\Html;



/**
 * @Tag
*/
class Tag
{

    /**
     * @param string $name
     * @param array $attributes
     * @return string
    */
    public function openTag(string $name, array $attributes = []): string
    {
        return sprintf("<{$name}%s>",
            $this->generateAttributes($attributes)
        );
    }




    /**
     * @param string $name
     * @return string
     */
    public function closeTag(string $name): string
    {
        return "</{$name}>";
    }




    /**
     * @param string $name
     * @param array $attributes
     * @param null $content
     * @return string
    */
    public function doubleTag(string $name, array $attributes = [], $content = null): string
    {
        return sprintf("%s%s%s", $this->openTag($name, $attributes), $content, $this->closeTag($name));
    }



    /**
     * Generate attributes
     *
     * @param array $attributes
     * @return string
    */
    public function generateAttributes(array $attributes): string
    {
        $str = [];

        foreach ($attributes as $key => $value) {
            if (is_string($key)) {
                $str[] = sprintf(' %s="%s"', $key, $value);
            }
        }

        return join('', $str);
    }

}