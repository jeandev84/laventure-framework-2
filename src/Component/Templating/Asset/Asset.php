<?php
namespace Laventure\Component\Templating\Asset;


/**
 * @Asset
*/
class Asset implements AssetInterface
{

     /**
      * @var string
     */
     protected $url;



     /**
      * @var array
     */
     protected $styles = [];



     /**
      * @var array
     */
     protected $scripts = [];



     /**
      * @var array
     */
     protected $extensions = [
         'styles'  => 'css',
         'scripts' => 'js'
     ];




     /**
      * Asset constructor
      *
      * @param string|null $url
     */
     public function __construct(string $url = null)
     {
          if ($url) {
              $this->setBaseURL($url);
          }
     }



     /**
      * @param string $url
      * @return $this
     */
     public function setBaseURL(string $url): self
     {
          $this->url = rtrim($url, '\\/');

          return $this;
     }



     /**
      * Add css link
      *
      * @param string $style
     */
     public function css(string $style)
     {
         $this->styles[] = $style;
     }



     /**
      * @param array $styles
     */
     public function addStyles(array $styles)
     {
         $this->styles = array_merge($this->styles, $styles);
     }



     /**
      * Get css data
      *
      * @return array
     */
     public function getStyles(): array
     {
         return $this->styles;
     }



     /**
      * Add js link
      *
      * @param string $script
     */
     public function js(string $script)
     {
         $this->scripts[] = $script;
     }



     /**
      * @param array $scripts
     */
     public function addScripts(array $scripts)
     {
         $this->scripts = array_merge($this->scripts, $scripts);
     }



     /**
      * Get css data
      *
      * @return array
     */
     public function getScripts(): array
     {
         return $this->scripts;
     }




     /**
      * Render asset path
      *
      * Example : url('/css/app.css')
      * Example : url('/css/app.js')
      * Example : url('/uploads/thumbs/some_hash.jpg')
      *
      * @param string $path
      * @return string
     */
     public function url(string $path): string
     {
          return $this->url . '/' . trim($path, '\\/');
     }



     /**
      * @param array $files
      * @return string
     */
     public function renderTemplates(array $files = []): string
     {
         if (! $files) {
             return implode(array_merge([
                  $this->renderStyles(),
                  $this->renderScripts()
             ]));
         }

         return $this->renderTemplateExternals($files);
     }




     /**
      * @param string $filename
      * @return string
     */
     public function renderStyle(string $filename): string
     {
          $filename = $this->url($filename);

          if (! $this->isStyleFile($filename)) {
              return "";
          }

          return sprintf('<link href="%s" rel="stylesheet">', $filename);
     }





     /**
      * @return string
     */
     public function renderStyles(): string
     {
         $templates = [];

         foreach ($this->styles as $filename) {
              $templates[] = $this->renderStyle($filename);
         }

         return join("\n", $templates);
     }




     /**
      * @param string $filename
      * @return string
     */
     public function renderScript(string $filename): string
     {
         $filename = $this->url($filename);

         if (! $this->isScriptFile($filename)) {
             return "";
         }

         return sprintf('<script src="%s" type="application/javascript"></script>', $filename);
     }




     /**
      * @return string
     */
     public function renderScripts(): string
     {
         $templates = [];

         foreach ($this->scripts as $filename) {
             $templates[] = $this->renderScript($filename);
         }

         return join("\n", $templates);
     }




     /**
      * @param array $files
      * @return string
     */
     public function renderTemplateExternals(array $files): string
     {
         $templates = [];

         foreach ($files as $filename) {
             if ($this->isStyleFile($filename)) {
                 $templates[] = $this->renderStyle($filename);
             }elseif ($this->isScriptFile($filename)) {
                 $templates[] = $this->renderScript($filename);
             }
         }

         return implode($templates);
     }




     /**
      * @param string $filename
      * @return bool
     */
     protected function isScriptFile(string $filename): bool
     {
          return $this->getExtension($filename) === $this->extensions['scripts'];
     }




     /**
      * @param string $filename
      * @return bool
     */
     protected function isStyleFile(string $filename): bool
     {
          return $this->getExtension($filename) === $this->extensions['styles'];
     }



     /**
      * @param string $filename
      * @return array|string|string[]
     */
     protected function getExtension(string $filename)
     {
         return pathinfo($filename, PATHINFO_EXTENSION);
     }

}