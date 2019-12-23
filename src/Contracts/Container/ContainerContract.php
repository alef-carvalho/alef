<?php


namespace Alef\Contracts\Container;

use Closure;
use Psr\Container\ContainerInterface;

interface ContainerContract extends ContainerInterface
{

    /**
     * Register a binding with the container.
     *
     * @param  string  $id
     * @param  Closure $closure
     * @return void
    */
    public function set($id, Closure $closure);

    /**
     * Register a shared binding in the container.
     *
     * @param  string  $id
     * @param  Closure $closure
     * @return void
    */
    public function singleton($id, Closure $closure);

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush();

}