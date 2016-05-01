<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

use Unika\Scrapper\ProccessJob;
use Unika\Scrapper\ProccessManager;

class ScrapRunnerCommand extends BaseCommand
{
    protected $finish = false;

    protected function configure()
    {
        $this
            ->setName('scrapper:run')
            ->setDescription('Launch scrapper service');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $manager = $container['ScrapperManager'];
        
        while(!$this->finish)
        {
            $job = $manager->fetch();
            if($job){
                $proccessor = new \Unika\Scrapper\Proccessor\OlxProccessor($container,$job);
                $proccessor->proccess();
                echo "done";
            }

            sleep(1);
        }
        
    }
}