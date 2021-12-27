<?php
/**
 *
 */
namespace Iayoo\ServiceHelper;

class ServiceCreateFactory
{
    protected $serviceDir;

    protected $appDir;

    protected $modelDir;

    protected $tmpDir;

    protected $service;

    protected $model;

    protected $prefix;

    protected $namespace;

    protected $parentService;

    /**
     * @return mixed
     */
    public function getServiceDir()
    {
        return $this->serviceDir;
    }

    /**
     * @param mixed $serviceDir
     */
    public function setServiceDir($serviceDir): void
    {
        $this->serviceDir = $serviceDir;
    }

    /**
     * @return mixed
     */
    public function getModelDir()
    {
        return $this->modelDir;
    }

    /**
     * @param mixed $modelDir
     */
    public function setModelDir($modelDir): void
    {
        $this->modelDir = $modelDir;
    }

    /**
     * @return mixed
     */
    public function getTmpDir()
    {
        return $this->tmpDir;
    }

    /**
     * @param mixed $tmpDir
     */
    public function setTmpDir($tmpDir): void
    {
        $this->tmpDir = $tmpDir;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $explName = explode("/",$service);
        if (count($explName) > 1){
            $this->service = $explName[count($explName)-1];
            unset($explName[count($explName)-1]);
            $this->prefix = $explName;
        }else{
            $this->prefix = [];
            $this->service = $service;
        }
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getNamespace($withPrefix = false)
    {
        if ($withPrefix && !empty($this->prefix)){
            return $this->namespace . "\\" . implode("\\",$this->prefix);
        }else{
            return $this->namespace;
        }
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getParentService()
    {
        return $this->parentService;
    }

    /**
     * @param mixed $parentService
     */
    public function setParentService($parentService): void
    {
        $this->parentService = $parentService;
    }

    /**
     * @return mixed
     */
    public function getAppDir()
    {
        return $this->appDir;
    }

    /**
     * @param mixed $appDir
     */
    public function setAppDir($appDir): void
    {
        $this->appDir = $appDir;
    }

    protected function getServiceFilePath(){
        return $this->serviceDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->prefix) . DIRECTORY_SEPARATOR . $this->service . ".php";
    }

    public function checkBaseService(){
        if (!file_exists($this->serviceDir . DIRECTORY_SEPARATOR . 'BaseService.php')){
            return false;
        }
        return true;
    }

    protected function checkServicesDirExists(){
        if (!is_dir($this->serviceDir) && is_writeable($this->appDir)){
            mkdir($this->serviceDir);
        }else{
            throw new \Exception("services dir not exsis");
        }
    }

    protected function generateBaseService(){
        $this->checkServicesDirExists();
        $content = file_get_contents($this->tmpDir . DIRECTORY_SEPARATOR . 'BaseService.tpl');

        $content = str_replace(
            ['{{SERVICE_NAMESPACE}}','{{SERVICE_PARENT}}'],
            [$this->getNamespace(),$this->getParentService()],
            $content
        );

        file_put_contents($this->serviceDir . DIRECTORY_SEPARATOR . 'BaseService.php',$content);
    }

    public function createService(){
        if (!$this->checkBaseService()){
            $this->generateBaseService();
        }
        if ($this->checkServiceFile()){
            throw new \Exception("service exists");
        }
        $content = file_get_contents($this->tmpDir . DIRECTORY_SEPARATOR . 'Service.tpl');
        $content = str_replace(
            ['{{SERVICE_NAMESPACE}}','{{SERVICE_PARENT}}','{{SERVICE_NAME}}'],
            [$this->getNamespace(true),$this->getNamespace() . "\\BaseService",$this->service],
            $content
        );

        file_put_contents($this->getServiceFilePath(),$content);
    }

    protected function checkServiceFile(){
        if (file_exists($this->getServiceFilePath())){
            throw new \Exception("service exists");
        }
        return false;
    }
}