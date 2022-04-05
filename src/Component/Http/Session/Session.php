<?php
namespace Laventure\Component\Http\Session;


use Laventure\Component\Http\Session\Utils\SessionStarter;
use Laventure\Component\Http\Session\Utils\SessionStarterInterface;

/**
 * @Session
*/
class Session implements SessionInterface
{


        /**
         * @var string
        */
        protected $flashKey = 'session.flash';




        /**
          * @var SessionStarterInterface
        */
        protected $starter;




        /**
          * @param SessionStarterInterface|null $starter
        */
        public function __construct(SessionStarterInterface $starter = null)
        {
              $this->starter = $starter ?? new SessionStarter();
        }




        /**
          * Start the session
          *
          * @return bool
        */
        public function start(): bool
        {
             return $this->starter->start();
        }





        /**
          * @param string $name
          * @param $value
          * @return void
        */
        public function set(string $name, $value): self
        {
            $_SESSION[$name] = $value;

             return $this;
        }





        /**
         * @param array $sessions
         * @return void
        */
        public function add(array $sessions)
        {
             foreach ($sessions as $name => $value) {
                 $this->set($name, $value);
             }
        }




        /**
          * @param string $name
          * @return bool
        */
        public function has(string $name): bool
        {
            return isset($_SESSION[$name]);
        }




        /**
         * @param string $name
         * @param $default
         * @return mixed|null
        */
        public function get(string $name, $default = null)
        {
            return $_SESSION[$name] ?? $default;
        }




        /**
         * @param string $name
         * @return void
        */
        public function remove(string $name)
        {
            unset($_SESSION[$name]);
        }



        /**
          * Remove all sessions
          *
          * @return void
        */
        public function clear()
        {
            $_SESSION = [];
            session_destroy();
        }




        /**
         * @return array
        */
        public function all(): array
        {
            return $_SESSION;
        }




        /**
         * @param string $flashKey
         * @return $this
        */
        public function setFlashKey(string $flashKey): self
        {
            $this->flashKey = $flashKey;

            return $this;
        }




        /**
         * @param string $type
         * @param string $message
         * @return Session
        */
        public function setFlash(string $type, string $message): self
        {
            $_SESSION[$this->flashKey][$type][] = $message;

            return $this;
        }




        /**
          * @param string $type
          * @return array|mixed
        */
        public function getFlash(string $type)
        {
            return $_SESSION[$this->flashKey][$type] ?? [];
        }




        /**
         * @return array|mixed
        */
        public function getFlashes()
        {
            return $_SESSION[$this->flashKey] ?? [];
        }





        /**
         * @param string $path
         * @return $this
        */
        public function saveTo(string $path): self
        {
            ini_set('session.save_path', $path);
            ini_set('session.gc_probability', 1);

            return $this;
        }




        /**
         * @return string
        */
        public function savePath(): string
        {
            return session_save_path();
        }
}