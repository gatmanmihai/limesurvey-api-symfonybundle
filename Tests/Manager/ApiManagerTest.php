<?php

namespace Gatman\LimeSurveyBundle\Tests\Manager;

use org\jsonrpcphp\JsonRPCClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Gatman\LimeSurveyBundle\Manager\ApiManager;

class ApiManagerTest extends TestCase
{
    public function testCreateClient()
    {
        /** @var EventDispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
        $dispatcherMock = $this->getMockBuilder(EventDispatcher::class)->disableOriginalConstructor()->getMock();

        /** @var JsonRPCClient|\PHPUnit_Framework_MockObject_MockObject $clientMock */
        $clientMock = $this->getMockBuilder(JsonRPCClient::class)->disableOriginalConstructor()->getMock();
        $clientMock->method('__call')->withConsecutive([
            'add_participants',
            [
                'session-key-as-string',
                'param1',
            ],
        ], [
            'release_session_key',
            ['session-key-as-string'],
        ])->willReturn(true);

        $manager = new ApiManager($dispatcherMock, $clientMock, 'session-key-as-string');

        $this->assertInstanceOf(JsonRPCClient::class, $manager->getClient());
        $this->assertTrue($manager->add_participants('param1'));
        $this->assertTrue($manager->releaseSessionKey());
    }
}
