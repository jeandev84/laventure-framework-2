<?php


/**
 * @param $context
 * @param bool $die
 * @return void
*/
function dump($context, bool $die = false)
{
    echo '<pre>' ;
    var_dump($context);
    echo '</pre>';
    if ($die) die;
}


function printArr($context, bool $die = false) {
    echo '<pre>';
    print_r($context);
    echo '</pre>';
    if ($die) die;
}


class Demo
{

    /**
     * @Route(methods="GET", path="/demo", name="demo.index")
     * @return \Laventure\Component\Http\Response\Response
     */
    public function index(): \Laventure\Component\Http\Response\Response
    {
        return new \Laventure\Component\Http\Response\Response("Demo::index");
    }
}


$demo = new Demo();

echo '<h3>Reflected class</h3>';
$reflectedObject = new ReflectionObject($demo);
dump($reflectedObject);


echo '<h3>Get methods</h3>';
$methods = $reflectedObject->getMethods();

dump($methods);


echo '<h3>Get Method docs</h3>';

$docs = $reflectedObject->getMethod('index')->getDocComment();


dump($docs);

$docArr = explode('*', $docs);

printArr($docArr);

// preg_match('#^--([^=]+)=(.*)$#i', $token,$params)

$route = [];

foreach ($docArr as $item) {
     if (stripos($item, '@Route') !== false) {
          $route = $item;
          break;
     }
}

$route = (array) str_replace(['@Route(', ')'], ['[', ']'], $route);
printArr($route);