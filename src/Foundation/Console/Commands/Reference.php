<?php

/*
|--------------------------------------------------------------------------------------------------------
|   Laventure Console application
|   Ex: $ php console                     (By default argument give list of commands)
|   Ex: $ php console list                (List all available application commands)
|   Ex: $ php console --help/-h           (Take help)
|   Ex: $ php console env:generate        (Generate file .env from .env.example)
|   Ex: $ php console env:key:generate    (Generate a new key APP_SECRET inside .env)
|   Ex: $ php console make:command        app:my:fake-command (Create a new command)
|   Ex: $ php console make:controller     SiteController (Make controller)
|   Ex: $ php console make:controller     FrontController -a=index,about,news,contact
|   Ex: $ php console make:controller     Admin/UserController -a=list,create...
|   Ex: $ php console make:controller     DemoController --resource (make resource)
|   Ex: $ php console make:controller     DemoController -actions=index,about,contact,portfolio
|   Ex: $ php console orm:fixtures:make   User
|   Ex: $ php console orm:fixtures:load
|   Ex: $ php console make:model  User
|   Ex: $ php console make:model  User
|   Ex: $ php console make:entity Cart
|   Ex: $ php console make:resource Product --api     (for making rest api)
|   Ex: $ php console make:resource Product           (for simple web resource)
|   Ex: $ php console server:run                      (run local  server php on port :8000 by default)
|---------------------------------------------------------------------------------------------------------
*/


use Laventure\Foundation\Console\Commands\Database\Migration\MigrationInstallCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationMakeCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationMigrateCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationResetCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationRollbackCommand;
use Laventure\Foundation\Console\Commands\Dotenv\GenerateEnvCommand;
use Laventure\Foundation\Console\Commands\Dotenv\GenerateKeyCommand;
use Laventure\Foundation\Console\Commands\Server\ServerRunCommand;

return [
    ServerRunCommand::class,
    // ServerStartCommand::class,
    // ServerRunCommand::class,
    // Database commands
    // DatabaseCreateCommand::class,
    // DatabaseDropCommand::class,
    // DatabaseBackupCommand::class,
    // DatabaseExportCommand::class,
    // DatabaseImportCommand::class,
    // Migration commands
    MigrationMakeCommand::class,
    MigrationInstallCommand::class,
    MigrationMigrateCommand::class,
    MigrationRollbackCommand::class,
    MigrationResetCommand::class,
    // Dotenv
    GenerateEnvCommand::class,
    GenerateKeyCommand::class
];