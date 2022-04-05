<?php
namespace Laventure\Component\Http\Session;


/**
 * @SessionInterface
*/
interface SessionInterface
{

     /**
      * Start a session
      *
      * @return mixed
     */
     public function start();



     /**
      * Set session
      *
      * @param string $name
      * @param $value
      * @return mixed
     */
     public function set(string $name, $value);




     /**
      * Determine if the given name exist in session
      *
      * @param string $name
      * @return mixed
     */
     public function has(string $name);




     /**
      * Get session by given name
      *
      * @param string $name
      * @param $default
      * @return mixed
     */
     public function get(string $name, $default = null);




     /**
      * Remove session by given name
      *
      * @param string $name
      * @return mixed
     */
     public function remove(string $name);





     /**
      * Get all sessions
      *
      * @return mixed
     */
     public function all();





     /**
      * remove all sessions
      *
      * @return mixed
     */
     public function clear();




    /**
     * @param string $type
     * @param string $message
     * @return mixed
    */
    public function setFlash(string $type, string $message);




    /**
     * @param string $type
     * @return mixed
    */
    public function getFlash(string $type);





    /**
     * @return mixed
    */
    public function getFlashes();
}