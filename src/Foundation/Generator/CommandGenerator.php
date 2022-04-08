<?php
namespace Laventure\Foundation\Generator;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loader\CommandLoader;

/**
 * @CommandGenerator
*/
class CommandGenerator extends StubGenerator
{


     /**
      * @var string
     */
     protected $commandPropertyName = 'defaultName';



     /**
      * @var CommandLoader
     */
     protected $loader;




     /**
      * @param Application $app
      * @param FileSystem $fileSystem
      * @param CommandLoader $loader
     */
     public function __construct(
         Application $app,
         FileSystem $fileSystem,
         CommandLoader $loader
     )
     {
           parent::__construct($app, $fileSystem);
           $this->loader = $loader;
     }




     /**
      * @return string
     */
     protected function getCommandPropertyName(): string
     {
          return $this->commandPropertyName;
     }



     /**
      * @param string $commandName
      * @return bool
     */
     public function generate(string $commandName): bool
     {
          $commandClass = $this->createCommandClass($commandName);

          $stub = $this->generateStub('command/template', [
              'CommandClass'             => $commandClass,
              'CommandNamespace'         => $this->loader->getNamespace(),
              'CommandPropertyName'      => $this->getCommandPropertyName(),
              'commandName'              => $commandName,
              'commandDescription'       => 'describe your command here...'
          ]);

          $targetPath = $this->generateCommandPath($commandClass);

          return $this->writeTo($targetPath, $stub);
     }



     /**
      * @param $commandName
      * @return string
     */
     public function createCommandClass($commandName): string
     {
         $commandClass = $this->makeName(ucfirst($commandName));

         if (stripos($commandName, ':') !== false) {
             $parts = explode(':', $commandName);
             $commandClass = $this->createCommandClassFromParts($parts);
             $this->commandPropertyName = 'name';
         }

         return $commandClass;
     }



     /**
      * @param array $parts
      * @return string
     */
     protected function createCommandClassFromParts(array $parts): string
     {
           $items = [];

           foreach ($parts as $part) {
               $items[] = ucfirst($part);
           }

           return $this->makeName(implode($items));
     }


     /**
      * @param string $commandClass
      * @return string
     */
     public function generateCommandPath(string $commandClass): string
     {
          return $this->loader->loadLocatePath($commandClass);
     }


     /**
      * @param string $commandName
      * @return string
     */
     private function makeName(string $commandName): string
     {
          return sprintf('%sCommand', $commandName);
     }
}