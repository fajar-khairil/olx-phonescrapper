<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

use Unika\Scrapper\ProccessJob;

class ScrapCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('scrapper:scrap')
            ->setDescription('Extracting Valuable data from clasified site olx,jualo')
            ->addArgument(
                'keyword',
                InputArgument::REQUIRED,
                'Keyword to scrap'
            )
            ->addArgument(
                'category',
                InputArgument::OPTIONAL,
                'Category to search'
            )
            ->addArgument(
                'city',
                InputArgument::OPTIONAL,
                'City to search'
            )
            ->addArgument(
                'limit',
                InputArgument::OPTIONAL,
                'limit page result'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $job = new ProccessJob(
            'olx',
            $input->getArgument('limit'),
            $input->getArgument('city'),
            $input->getArgument('keyword'),
            $input->getArgument('category')
        );

        $container = $this->getApplication()->getContainer();
        $job->setDB($container['illuminate_db']);

        $proccessor = new \Unika\Scrapper\Proccessor\OlxProccessor($container,$job);
        $proccessor->proccess();
    }

    // protected function fetchOlxCategory()
    // {
    //     // fetch olx category
    //     $crawler = new Crawler();
    //     $crawler->add(file_get_contents('source.html'));

    //     $container = $this->getApplication()->getContainer();
    //     $db = $container['illuminate_db'];

    //     $nodes = $crawler->filter('ul#categori-list > li');

    //     $now = date('Y-m-d H:i:s');
    //     $nodes->each(function($node,$i) use($db,$now){
    //         $node_link = $node->filter('a');
    //         $cat_id = $node_link->attr('data-id');
    //         $cat_name = $node_link->attr('data-name');

    //         try
    //         {
    //             $db->table('src_olx_categories')->insert([
    //                 'id'            =>  $cat_id,
    //                 'name'          =>  $cat_name,
    //                 'parent_id'     =>  null,
    //                 'created_at'    =>  $now
    //             ]);
    //         }
    //         catch(\Exception $e)
    //         {

    //         }
            
    //         $childs = $node->filter('ul.abs > li > a');
    //         if( $childs->count() > 0 ){
    //             $childs->each(function($child,$i)use($db,$cat_id,$now){
    //                 $child_id = $child->attr('data-id');
    //                 $child_name = $child->attr('data-name');

    //                 try
    //                 {
    //                     $db->table('src_olx_categories')->insert([
    //                         'id'            =>  $child_id,
    //                         'parent_id'     =>  $cat_id,
    //                         'name'          =>  $child_name,
    //                         'created_at'    =>  $now
    //                     ]);
    //                 }
    //                 catch(\Exception $e)
    //                 {

    //                 }
    //             });
    //         }
    //     });
    // }
}