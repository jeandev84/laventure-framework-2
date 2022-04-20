<?php
namespace Laventure\Foundation\Routing;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Templating\Renderer\Renderer;



/**
 * @DefaultController
*/
class DefaultController extends Controller
{

       /**
        * @var bool
       */
       protected $layout = false;




       /**
        * DefaultController constructor
       */
       public function __construct(Renderer $renderer, FileSystem $fs)
       {
            $renderer->resourcePath(__DIR__.'/Resources/views')
                     ->cacheDir($fs->locate('/storage/cache/framework/views'))
                     ->compress(false);
       }




       /**
        * @return Response
       */
       public function index(): Response
       {
            return $this->render('welcome.php');
       }
}