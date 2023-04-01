<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1d7352091ac878676fd21a6c2326a2ce
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Matrod\\PhpAssignementMarketplace\\' => 33,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Matrod\\PhpAssignementMarketplace\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'B' => 
        array (
            'Bramus' => 
            array (
                0 => __DIR__ . '/..' . '/bramus/router/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1d7352091ac878676fd21a6c2326a2ce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1d7352091ac878676fd21a6c2326a2ce::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit1d7352091ac878676fd21a6c2326a2ce::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit1d7352091ac878676fd21a6c2326a2ce::$classMap;

        }, null, ClassLoader::class);
    }
}
