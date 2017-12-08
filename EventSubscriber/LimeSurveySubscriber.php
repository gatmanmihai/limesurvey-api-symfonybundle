<?php

namespace Youmesoft\LimeSurveyBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Youmesoft\LimeSurveyBundle\Event\LimeSurveyRequestEvent;
use Youmesoft\LimeSurveyBundle\YoumesoftLimeSurveyEvents;

class LimeSurveySubscriber implements EventSubscriberInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var array */
    protected $config;

    public function __construct(ContainerInterface $container, array $config)
    {
        $this->container = $container;
        $this->config    = $config;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            YoumesoftLimeSurveyEvents::LS_REQUEST => 'onLsRequest',
        ];
    }

    public function onLsRequest(LimeSurveyRequestEvent $event)
    {
        if ($this->isDebugEnabled()) {
            $this->logRequest($event->getName(), $event->getArguments(), $event->getResponse());
        }
    }

    /**
     * @return boolean
     */
    protected function isDebugEnabled()
    {
        return $this->config['debug']['enabled'];
    }

    protected function logRequest($name, $arguments, $response)
    {
        $path = $this->config['debug']['path'];
        if (!$path) {
            $path = "{$this->container->getParameter('kernel.root_dir')}/../var/lime-survey";
        }

        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        $env = $this->container->getParameter('kernel.environment');

        $date         = date('Y-m-d H:i:s');
        $jsonArgs     = json_encode($arguments);
        $jsonResponse = json_encode($response);

        $fp = fopen("{$path}/{$env}.log", 'a+');
        fwrite($fp, "\n\nDate: {$date}\nRequest name: {$name}\nRequest args: {$jsonArgs}\nResponse: {$jsonResponse}\n\n----------------");
        fclose($fp);
    }
}