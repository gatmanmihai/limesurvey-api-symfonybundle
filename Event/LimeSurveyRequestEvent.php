<?php

namespace Youmesoft\LimeSurveyBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class LimeSurveyRequestEvent extends Event
{    
    /** @var string */
    protected $name;
    /** @var array */
    protected $arguments;
    /** @var mixed */
    protected $response;

    public function __construct($name, $arguments, $response)
    {
        $this->name      = $name;
        $this->arguments = $arguments;
        $this->response  = $response;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}