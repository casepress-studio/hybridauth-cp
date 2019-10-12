<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfbfb0c32a459fed72f9f23c694ffe44c {
  public static $prefixLengthsPsr4 = [
    'H' =>
      [
        'Hybridauth\\' => 11,
      ],
  ];

  public static $prefixDirsPsr4 = [
    'Hybridauth\\' =>
      [
        0 => __DIR__ . '/..' . '/hybridauth/hybridauth/src',
      ],
  ];

  public static function getInitializer(ClassLoader $loader) {
    return \Closure::bind(function() use ($loader) {
      $loader->prefixLengthsPsr4 = ComposerStaticInitfbfb0c32a459fed72f9f23c694ffe44c::$prefixLengthsPsr4;
      $loader->prefixDirsPsr4    = ComposerStaticInitfbfb0c32a459fed72f9f23c694ffe44c::$prefixDirsPsr4;

    }, null, ClassLoader::class);
  }
}
