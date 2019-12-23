<?php


namespace Alef\Contracts\Container;

use Closure;
use Psr\Container\ContainerInterface;

interface ContainerContract extends ContainerInterface
{

    /**
     * Register a new binding in container.
     *
     * @param string $id
     * @param Closure $closure
     * @return void
     */
    public function set($id, Closure $closure);

    /**
     * Extend a binding definition without necessarily loading that object.
     *
     * @param $id
     * @param Closure $closure
     */
    public function extend($id, Closure $closure);

    /**
     * Register a singleton binding in container.
     *
     * @param string $id
     * @param Closure $closure
     * @return void
     */
    public function singleton($id, Closure $closure);

    /**
     * Clear container bindings.
     *
     * @return void
     */
    public function flush();

}