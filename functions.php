<?php

/**
 * Creates an instance of the class specified by $className (wrapper for \Yii::$container->get())
 * @param string $class
 * @param array $params
 * @param array $config
 * @return mixed
 * @see \yii\di\Container::get()
 */
function make($class, $params = [], $config = [])
{
    return \Yii::$container->get($class, $params, $config);
}

/**
 * Registers a class definition with the DI container (wrapper for \Yii::$container->set())
 * @param string $class
 * @param mixed $definition
 * @param array $params
 * @see \yii\di\Container::set()
 */
function bind($class, $definition = [], array $params = [])
{
    \Yii::$container->set($class, $definition, $params);
}
