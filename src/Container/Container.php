<?php

/*
 * File        : Container.php
 * Description : Dependency Injection Container
 * Authors     : Alef Carvalho <alef@alefcarvalho.com.br>
*/

namespace Alef\Container;

use Closure;
use ArrayAccess;
use Alef\Contracts\Container\ContainerContract;
use Alef\Container\Exceptions\UnknownIdentifierException;

final class Container implements ArrayAccess, ContainerContract
{

    /**
     * Global singleton container instance.
     *
     * @var static
     */
    protected static $instance;

    /**
     * Container's bindings.
     *
     * @var array[]
     */
    protected $bindings = [];

    /*
     * Container Methods
    */

    /**
     * Determine if the key is bound in container.
     *
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->bindings[$id]);
    }

    /**
     * Retrieve a binding in container.
     *
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {

        //check if key exists
        if ($this->has($id)) {
            return $this->bindings[$id]($this);
        }

        throw new UnknownIdentifierException($id);

    }

    /**
     * Register a new binding in container.
     *
     * @param string $id
     * @param Closure $closure
     * @return void
     */
    public function set($id, Closure $closure)
    {
        $this->bindings[$id] = $closure;
    }

    /**
     * Extend a binding definition without necessarily loading that object.
     *
     * @param $id
     * @param Closure $closure
     */
    public function extend($id, Closure $closure)
    {

        //retrieve original binding
        $original = $this->get($id);

        //make a new closure
        $extended = function ($container) use ($closure, $original) {
            return $closure($original, $container);
        };

        //override current binding
        $this->set($id, $extended);

    }

    /**
     * Register a singleton binding in container.
     *
     * @param string $id
     * @param Closure $closure
     * @return void
     */
    public function singleton($id, Closure $closure)
    {

        //make a singleton closure
        $this->bindings[$id] = function () use ($closure) {

            static $singleton;

            if ($singleton === null) {
                $singleton = $closure($this);
            }

            return $singleton;

        };

    }


    /**
     * Clear container bindings.
     *
     * @return void
     */
    public function flush()
    {
        $this->bindings = [];
    }

    /**
     * Get global singleton instance of the container.
     *
     * @return static
    */
    public static function instance()
    {

        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;

    }


    /*
     * Magic Methods
    */


    /**
     * Get value from container dynamically.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Bind value to container dynamically.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }

    /*
     * ArrayAccess Methods
    */

    /**
     * Check if container offset exists.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get value from container at a given offset.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a container value at a given offset.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a container value at a given offset.
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->bindings[$key], $this->cache[$key]);
    }

}