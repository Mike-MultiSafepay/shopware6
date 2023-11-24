<?php declare(strict_types=1);
use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->addCallingPlugin()
    ->addActivePlugins('MltisafeMultiSafepay')
    ->setForceInstallPlugins(true)
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('MultiSafepay\\Shopware6\\Tests\\', __DIR__);
