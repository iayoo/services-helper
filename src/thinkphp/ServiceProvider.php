<?php
/**
 *
 */


namespace Iayoo\ServiceHelper\thinkphp;

use Iayoo\ServiceHelper\ServiceFactory;
use think\Model;

class ServiceProvider extends ServiceFactory
{
    /** @var Model */
    protected $modelInstance;

    public function __construct()
    {
        if (!empty($this->model) && class_exists($this->model)){
            $model = $this->model;
            $this->modelInstance = new $model;
        }
    }

    public function find($data){
        return $this->modelInstance->find($data);
    }
}
