<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/ApcClassLoader.php';
require_once __DIR__.'/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
require_once __DIR__.'/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/MapClassLoader.php';

use Symfony\Component\ClassLoader\ApcClassLoader,
    Symfony\Component\ClassLoader\UniversalClassLoader,
    Symfony\Component\ClassLoader\MapClassLoader;

$namespaces = include __DIR__.'/../vendor/composer/autoload_namespaces.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces($namespaces);

$loader = new ApcClassLoader('open_dradio_rest_api.', $loader);
$loader->register(true);

$mapping = include __DIR__.'/../vendor/composer/autoload_classmap.php';

if (!empty($mapping)) {

    $loader = new MapClassLoader($mapping);
    $loader->register();
}