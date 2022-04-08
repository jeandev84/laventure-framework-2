<?php
namespace Laventure\Foundation\Routing;


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
       public function __construct(Renderer $renderer)
       {
            $renderer->resourcePath(__DIR__.'/Resources/views')
                     ->cache(false)
                     ->compress(false)
            ;
       }




       /**
        * @return Response
       */
       public function index(): Response
       {
            return $this->render('default/index.php');
       }
}