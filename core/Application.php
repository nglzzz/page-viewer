<?php

declare(strict_types=1);

namespace Core;

final class Application
{
    private static $instance = null;
    private ?Autoload $autoload;
    private Router $router;
    private Config $config;
    private ConnectionInterface $connection;
    private Debug $debug;

    private function __construct()
    {
        define('START_TIME', microtime(true));

        $this->registerDependencies();
    }

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function run()
    {
        $request = new Request();
        $result = $this->getRouter()->dispatch($request);

        if (is_string($result)) {
            echo $result;
        }
    }

    private function registerDependencies(): void
    {
        $this->registerAutoload();
        $this->registerConfig();
        $this->registerRouter();
        $this->registerConnection();
        $this->registerDebug();
    }

    private function registerAutoload(): void
    {
        require_once __DIR__ . '/Autoload.php';

        $this->autoload = new Autoload([
            'Core' => __DIR__ . '/',
            'App' => dirname(__DIR__) . '/src',
        ]);
        $this->autoload->register();
    }

    private function registerConfig(): void
    {
        $this->config = new Config(__DIR__ . '/../.env');
    }

    private function registerRouter(): void
    {
        $this->router = new Router();
    }

    private function registerConnection(): void
    {
        $class = ConnectionFactory::create($this->config->get('DB_TYPE', 'mysql'));

        $this->connection = new $class(
            $this->config->get('DB_HOST', 'localhost'),
            $this->config->get('DB_DATABASE'),
            $this->config->get('DB_USER'),
            $this->config->get('DB_PASSWORD'),
            (int) $this->config->get('DB_PORT', 3306),
        );
    }

    private function registerDebug()
    {
        $configDebug = $this->config->get('DEBUG', false);
        $this->debug = new Debug(!empty($configDebug) && $configDebug !== 'false');
    }
}
