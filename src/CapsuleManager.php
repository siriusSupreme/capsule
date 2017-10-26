<?php

namespace Sirius\Capsule;

use Sirius\Support\Repository;
use Sirius\Container\Container;
use Sirius\Support\Str;

trait CapsuleManager
{
    /**
     * 配置 实例 前缀.
     *
     * @var bool|string
     */
    protected $abstractPrefix=false;

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
    protected $container;

    /**
     * Setup the IoC container instance.
     *
     * @param  \Sirius\Container\Container  $container
     * @return void
     */
    protected function setupContainer(Container $container=null)
    {
        $this->container = $container??new Container;

        $prefix= $this->getAbstractPrefix();

        if (! $this->container->bound( $prefix.'config')) {
            $this->container->instance( $prefix .'config', new Repository);
        }
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
     * Get the IoC container instance.
     *
     * @return \Sirius\Container\Container
     */
    public function getContainer()
    {
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
        $this->container = $container;
    }
}
