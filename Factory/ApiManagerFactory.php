<?php

namespace Youmesoft\LimeSurveyBundle\Factory;

use org\jsonrpcphp\JsonRPCClient;
use Youmesoft\LimeSurveyBundle\Manager\ApiManager;

class ApiManagerFactory
{
    /** @var array */
    protected $credentials;

    public function __construct(array $credentials = [])
    {
        $this->credentials = $credentials;
    }

    public function createApiManager()
    {
        $client     = new JsonRPCClient($this->credentials['url']);
        $sessionKey = $client->get_session_key($this->credentials['username'], $this->credentials['password']);

        return new ApiManager($client, $sessionKey);
    }
}