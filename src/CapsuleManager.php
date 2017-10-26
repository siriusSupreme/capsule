<?php

namespace Sirius\Capsule;

use Sirius\Support\Repository;
use Sirius\Container\Container;
use Sirius\Support\Str;

trait CapsuleManager
{
    /**
     * 配置 实例 de 抽象前缀.
     *
     * @var bool|string
     */
    protected $abstractPrefix=false;

    /**
     * The config instance.
     *
     * @var \Sirius\Support\Repository
     */
    protected $config = null;

    /**
     * The current globally used instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * The container instance.
     *
     * @var \Sirius\Container\Container
     */
    protected $container=null;

    /**
     * Setup the IoC container instance.
     *
     * @param  \Sirius\Container\Container  $container
     * @return void
     */
    protected function setupContainer(Container $container=null)
    {
        $this->container = $container ?? $this->container ?? new Container;

        $prefix= $this->getAbstractPrefix();

        if (! $this->container->bound( $prefix.'config')) {
            $this->container->instance( $prefix .'config', new Repository);
        }

        $this->config=$this->container->make( $prefix . 'config');
    }

    protected function getAbstractPrefix(){
      if ($this->abstractPrefix===true){
        $this->abstractPrefix=Str::random().'.';
      }elseif (is_string( $this->abstractPrefix) && trim( $this->abstractPrefix)!==''){
        $this->abstractPrefix=Str::finish( $this->abstractPrefix,'.');
      }else{
        $this->abstractPrefix='';
      }

      return $this->abstractPrefix;
    }

    /**
     * Make this capsule instance available globally.
     *
     * @return void
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    /**
     * Get the config instance.
     *
     * @return \Sirius\Support\Repository
     *
     * @throws \Exception
     */
    public function getConfigInstance() {
      if ( is_null( $this->config ) ) {
        throw new \Exception( 'The config instance hsa not set yet!' );
      }

      return $this->config;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Sirius\Container\Container
     *
     * @throws \Exception
     */
    public function getContainer()
    {
      if (is_null( $this->container)){
        throw new \Exception('The container hsa not set yet!');
      }
        return $this->container;
    }

    /**
     * Set the IoC container instance.
     *
     * @param  \Sirius\Container\Container  $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->setupContainer( $container);
    }
}
