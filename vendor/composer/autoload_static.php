<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit510fa311ad41b03f81da02dea7a40149
{
    public static $prefixesPsr0 = array (
        'o' => 
        array (
            'org\\bovigo\\vfs' => 
            array (
                0 => __DIR__ . '/..' . '/mikey179/vfsStream/src/main/php',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit510fa311ad41b03f81da02dea7a40149::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
