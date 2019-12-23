<?php

/*
 * File        : UnknownIdentifierException.php
 * Description : Exception triggered when attempting to access non-existent binding in container.
 * Authors     : Alef Carvalho <alef@alefcarvalho.com.br>
*/

namespace Alef\Container\Exceptions;

class UnknownIdentifierException extends \InvalidArgumentException
{

    public function __construct($id)
    {
        parent::__construct(
            sprintf('Identifier "%s" is not defined in container.', $id)
        );
    }

}