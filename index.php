<?php

require 'vendor/autoload.php';

$container = \Alef\Container\Container::instance();

foreach (range(1, 100) as $i) {

    $container->set("_$i", function () use($i) {
        return $i;
    });

    $container->extend("_$i", function () use($i) { return $i * 2; });

    dump($container->get("_$i"));
}