<?php
namespace Laventure\Foundation\Console\Commands\Dotenv;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\BaseCommand;


/**
 * @GenerateKeyCommand
*/
class GenerateKeyCommand extends BaseCommand
{

    /**
     * @var string
    */
    protected $name = 'env:key:generate';




    /**
     * @var string
    */
    protected $description = 'Generate a env file for configuration application.';





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $secret = sprintf('APP_SECRET=%s', $this->makeHash());

        $oldContent = $this->fileSystem->read('.env');
        $newContent = preg_replace('/APP_SECRET=(.*)/', $secret, $oldContent);

        $this->fileSystem->remove('.env');

        if($this->fileSystem->write('.env', $newContent)) {
            $output->writeln("New secret key '{$secret}' has been created in .env");
        }

        return Command::SUCCESS;
    }



    /**
     * @return string
    */
    protected function makeHash(): string
    {
         return md5($this->app->getName(). uniqid(rand(), true));
    }
}