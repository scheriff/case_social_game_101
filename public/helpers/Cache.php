<?php

require __DIR__ . '/../../vendor/predis/predis/autoload.php';

Predis\Autoloader::register();

class Cache
{
    /* @var $client Predis\Client */
    public $client = null;

    public function __construct()
    {
        $this->client = new Predis\Client(['host' => 'redis']);
    }

    public function set($key, $value)
    {
        $response = $this->client->transaction()->set($key, $value)->get($key)->execute();
        return array_pop($response);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }
}