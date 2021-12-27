<?php
/**
 *
 */


namespace Iayoo\ServiceHelper\thinkphp;


use Iayoo\ServiceHelper\ServiceCreateFactory;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class ServiceCreateCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('service/create/command')
            ->addOption("service",'s',Option::VALUE_REQUIRED,'service name')
            ->setDescription('ServiceCreateCommand');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->checkOption();
        $factory = new ServiceCreateFactory();

        $factory->setService($this->input->getOption('service'));

        $factory->setModelDir(app_path() . DIRECTORY_SEPARATOR . "model");
        $factory->setServiceDir(app_path() . DIRECTORY_SEPARATOR . "services");
        $factory->setTmpDir(__DIR__ . DIRECTORY_SEPARATOR . 'tpl');
        $factory->setNamespace('app\services');
        $factory->setAppDir(app_path());
        $factory->setParentService('\Iayoo\ServiceHelper\thinkphp\ServiceProvider');
        $factory->createService();
    }

    protected function checkOption(){
        if (!$this->input->hasOption('service')){
            throw new \Exception('serivce 不能为空！');
        }
    }
}