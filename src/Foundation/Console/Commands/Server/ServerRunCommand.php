<?php
namespace Laventure\Foundation\Console\Commands\Server;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Server\Common\AbstractServerCommand;



/**
 * @ServerRunCommand
*/
class ServerRunCommand extends AbstractServerCommand
{


    /**
     * @var string
     */
    protected $name = 'server:run';



    /**
     * @var string
     */
    protected $description = 'Lunch application server on the specific port.';



    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {

        foreach ($this->getHeadMessage() as $message) {
            $output->writeln($message);
        }


        $output->exec($this->displayServerExec($this->host));

        return Command::SUCCESS;
    }


    /**
     * @param array $config
     * @return array
     */
    protected function getHeadMessage(array $config = []): array
    {
        return [
            sprintf('Server Listen on the port :%s', $this->getPort()),
            sprintf('Open to your browser next link %s', $this->getLink())
        ];
    }


    /**
     * @param string $host
     * @return string
     */
    protected function displayServerExec(string $host = ''): string
    {
        $cmd = sprintf('php -S %s -t public', $host);

        if ('127.0.0.1:8000' !== $host) {
            $cmd = sprintf('php -S %s', $host);
        }

        return sprintf('%s -d display_errors=1', $cmd);
    }

}