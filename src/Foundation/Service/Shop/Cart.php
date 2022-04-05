<?php
namespace Laventure\Foundation\Service\Shop;


use Laventure\Component\Http\Session\SessionInterface;


/**
 * @Cart
*/
class Cart
{

    /**
     * @var string
    */
    protected $name = 'cart';



    /**
     * @var SessionInterface
    */
    protected $session;




    /**
     * @param SessionInterface $session
    */
    public function __construct(SessionInterface $session)
    {
          $this->session = $session;
    }




    /**
     * Add item to cart
     *
     * @param $id
     * @return void
    */
    public function add($id)
    {
        $cart = $this->session->set($this->name, []);

        if (! empty($cart[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $this->session->set($this->name, $cart);
    }




    /**
     * Get cart
     * @return mixed
    */
    public function get()
    {
        return $this->session->get($this->name);
    }



    /**
     * Remove cart
     *
     * @return void
    */
    public function remove()
    {
        $this->session->remove($this->name);
    }




    /**
     * @param $id
     * @return void
    */
    public function delete($id)
    {
        $cart = $this->session->get($this->name, []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }




    /**
     * @param $id
     * @return void
     */
    public function decrease($id)
    {
        $cart = $this->session->get($this->name, []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }

        $this->session->set($this->name, $cart);
    }

}