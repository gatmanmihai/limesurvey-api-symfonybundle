<?php

namespace Youmesoft\LimeSurveyBundle\Manager;

use org\jsonrpcphp\JsonRPCClient;

class ApiManager
{
    /** @var JsonRPCClient */
    protected $client;
    protected $sessionKey;

    /** @var array */
    protected $credentials;

    public function __construct(JsonRPCClient $client, $sessionKey)
    {
        $this->client     = $client;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return JsonRPCClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        array_unshift($arguments, $this->getSessionKey());

        return call_user_func_array([
            $this->client,
            $name,
        ], $arguments);
    }

    /**
     * @return mixed
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    public function __destruct()
    {
        $this->releaseSessionKey();
    }

    /**
     * @return mixed
     */
    public function releaseSessionKey()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->client->release_session_key($this->getSessionKey());
    }
}