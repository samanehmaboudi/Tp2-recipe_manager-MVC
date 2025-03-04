<?php
namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View {
    private static $twig = null;

    private static function init() {
        if (self::$twig === null) {
            $loader = new FilesystemLoader(__DIR__ . '/../../views');
            self::$twig = new Environment($loader, [
                'cache' => __DIR__ . '/../../cache', // Activer le cache pour de meilleures performances
                'debug' => true // Activer le mode debug pour Twig
            ]);

            self::setGlobals();
        }
    }

    private static function setGlobals() {
        self::$twig->addGlobal('asset', ASSET);
        self::$twig->addGlobal('base', BASE);
    }

    public static function render($template, $data = []) {
        self::init();
        echo self::$twig->render($template . ".twig", $data);
    }

    public static function redirect($url) {
        header("Location: " . BASE . "/$url");
        exit;
    }
}
