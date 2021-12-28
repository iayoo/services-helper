<?php
/**
 *
 */


namespace Iayoo\ServiceHelper\thinkphp;

use Iayoo\ServiceHelper\ServiceFactory;
use think\facade\Request;
use think\Model;

class ServiceProvider extends ServiceFactory
{
    /** @var Model */
    protected $modelInstance;

    /** @var Model */
    protected $model;

    protected $page = 1;

    protected $limit = 20;

    public function __construct()
    {
        if (!empty($this->model) && class_exists($this->model)){
            $model = $this->model;
            $this->modelInstance = new $model;
        }
        $this->setPageParams();
    }

    public function get($data){
        return $this->model::find($data);
    }

    public function setPageParams(){
        /** @var Request $request */
        $this->page = \request()->param('page',1);
        $this->limit = \request()->param('limit',1);
    }
}
