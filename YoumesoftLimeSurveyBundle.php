<?php

namespace Youmesoft\LimeSurveyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youmesoft\LimeSurveyBundle\DependencyInjection\Compiler\LimeSurveyCompilerPass;

class YoumesoftLimeSurveyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LimeSurveyCompilerPass());
    }
}
