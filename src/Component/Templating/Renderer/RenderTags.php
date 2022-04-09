<?php
namespace Laventure\Component\Templating\Renderer;


/**
 * @RenderTags
*/
class RenderTags
{


    /**
     * @return int[]|string[]
     */
    protected function getTagKeys(): array
    {
        return array_keys($this->getContentTags());
    }




    /**
     * @return string[]
     */
    protected function getTagValues(): array
    {
        return array_values($this->getContentTags());
    }



    
    /**
     * @param string $content
     * @return array|string|string[]
    */
    public function replaceTags(string $content)
    {
        return str_replace($this->getTagKeys(), $this->getTagValues(), $content);
    }




    /**
     * @return string[]
    */
    public function getContentTags(): array
    {
        return [
            '{%'        =>  "<?=",
            '%}'        =>  ";?>",
            '{{'        => "<?=",
            '}}'        => ";?>",
            '@if'       =>  "<?php if",
            '@endif'    =>  "<?php endif; ?>",
            '@loop'     => "<?php foreach",
            '@endloop'  =>  "<?php endforeach; ?>",
        ];
    }




    /**
     * @return string[]
    */
    public function getContentTagsToReviews(): array
    {
        return [
            '{%'        =>  "<?php ",
            '%}'        =>  ";?>",
            '{{'        => "<?=",
            '}}'        => ";?>",
            '@if'       =>  "<?php if",
            '@endif'    =>  "<?php endif; ?>",
            '@loop'     => "<?php foreach",
            '@endloop'  =>  "<?php endforeach; ?>",
        ];
    }
}