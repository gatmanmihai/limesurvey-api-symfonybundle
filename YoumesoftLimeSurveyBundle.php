<?php

namespace Gatman\LimeSurveyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Gatman\LimeSurveyBundle\DependencyInjection\Compiler\LimeSurveyCompilerPass;

class GatmanLimeSurveyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LimeSurveyCompilerPass());
    }
}
