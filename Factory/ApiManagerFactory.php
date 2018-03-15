<?php

namespace Gatman\LimeSurveyBundle\Factory;

use org\jsonrpcphp\JsonRPCClient;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Gatman\LimeSurveyBundle\EventSubscriber\LimeSurveySubscriber;
use Gatman\LimeSurveyBundle\Manager\ApiManager;

class ApiManagerFactory
{
    /** @var array */
    protected $credentials;

    public function __construct(array $credentials = [])
    {
        $this->credentials = $credentials;
    }

    public function createApiManager(LimeSurveySubscriber $subscriber)
    {
        $client     = new JsonRPCClient($this->credentials['url']);
        $sessionKey = $client->get_session_key($this->credentials['username'], $this->credentials['password']);
        $dispatcher = $this->createApiDispatcher($subscriber);

        return new ApiManager($dispatcher, $client, $sessionKey);
    }

    public function createApiDispatcher(LimeSurveySubscriber $subscriber)
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);

        return $dispatcher;
    }
}