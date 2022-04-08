<?php
namespace Laventure\Foundation\Routing;


use Laventure\Component\Authentication\Database\User;
use Laventure\Component\Authentication\Database\UserInterface;
use Laventure\Component\Container\Container;
use Laventure\Component\Container\Contract\ContainerAwareInterface;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Database\Manager;
use Laventure\Component\Http\Response\JsonResponse;
use Laventure\Component\Http\Response\RedirectResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Templating\Renderer\RenderLayoutInterface;


/**
 * @Controller
*/
abstract class Controller implements ContainerAwareInterface
{

    /**
     * @var Container
    */
    protected $container;




    /**
     * @var mixed
    */
    protected $layout;




    /**
     * @param ContainerInterface $container
     * @return void
    */
    public function setContainer(ContainerInterface $container)
    {
         $this->container = $container;
    }




    /**
     * @param $layout
     * @return void
    */
    public function setLayout($layout)
    {
         $this->layout = $layout;
    }



    /**
     * @return bool|mixed
    */
    private function getLayout()
    {
         if ($this->layout) {
             $this->container->instance("@layout", $this->layout);
         }

         return $this->layout;
    }




    /**
     * @return ContainerInterface
    */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }




    /**
     * @param $id
     * @return mixed
    */
    public function get($id)
    {
        return $this->container->get($id);
    }



    /**
     * @param string $template
     * @param array $data
     * @param Response|null $response
     * @return Response
    */
    public function render(string $template, array $data = [], Response $response = null): Response
    {
           $renderer = $this->get('view');

           if ($renderer instanceof RenderLayoutInterface) {
               $renderer->withLayout($this->getLayout());
           }

           $output = $renderer->render($template, $data);

           if (! $response) {
               $response = new Response();
           }

           $response->setContent($output);

           return $response;
    }




    /**
     * @param $template
     * @param array $data
     * @return mixed
    */
    public function renderHtml($template, array $data = [])
    {
         return $this->get('view')->render($template, $data);
    }




    /**
     * @param string $path
     * @param int $code
     * @return RedirectResponse
    */
    public function redirectTo(string $path, int $code = 301): RedirectResponse
    {
        return new RedirectResponse($path, $code);
    }




    /**
     * @return RedirectResponse
     */
    public function redirectToHome(): RedirectResponse
    {
        return $this->redirectTo('/');
    }




    /**
     * @param string $name
     * @param array $parameters
     * @param int $statusCode
     * @return RedirectResponse
    */
    public function redirectToRoute(string $name, array $parameters = [], int $statusCode = 301): RedirectResponse
    {
        $path = $this->get('router')->generate($name, $parameters);

        return $this->redirectTo($path, $statusCode);
    }




    /**
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
    */
    public function json(array $data, int $statusCode = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $statusCode, $headers);
    }



    /**
     * @return UserInterface
    */
    public function getUser(): UserInterface
    {
        // todo implements
        return new User();
    }



    /**
     * @param string $name
     * @return void
    */
    public function middleware(string $name)
    {
         // get stack route middlewares from App/Http/Kernel and  set routeMiddlewares
    }





    /**
     * Get Database manager
     *
     * @return Manager
    */
    public function getDB(): Manager
    {
         return $this->container->get('db.laventure');
    }
}