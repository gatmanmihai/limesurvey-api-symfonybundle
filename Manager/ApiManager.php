<?php

namespace Gatman\LimeSurveyBundle\Manager;

use org\jsonrpcphp\JsonRPCClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gatman\LimeSurveyBundle\Event\LimeSurveyRequestEvent;
use Gatman\LimeSurveyBundle\EventSubscriber\LimeSurveySubscriber;
use Gatman\LimeSurveyBundle\GatmanLimeSurveyEvents;

class ApiManager
{
    /** @var LimeSurveySubscriber */
    protected $subscriber;
    /** @var JsonRPCClient */
    protected $client;
    protected $sessionKey;

    /** @var array */
    protected $credentials;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, JsonRPCClient $client, $sessionKey)
    {
        $this->client     = $client;
        $this->sessionKey = $sessionKey;
        $this->dispatcher = $dispatcher;
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
     * @return array|mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        array_unshift($arguments, $this->getSessionKey());

        $exception = null;
        try {
            $response = call_user_func_array([
                $this->client,
                $name,
            ], $arguments);
        } catch (\Exception $e) {
            $exception = $e;

            $response = [
                'exception' => true,
                'message'   => $exception->getMessage(),
                'code'      => $exception->getCode(),
            ];
        }

        $event = new LimeSurveyRequestEvent($name, $arguments, $response);
        $this->dispatcher->dispatch(GatmanLimeSurveyEvents::LS_REQUEST, $event);

        if ($exception) {
            throw $exception;
        }

        return $response;
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