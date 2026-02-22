<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Enum\CrudEvents;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\EventListener\SettingsSubscriber;

final class SettingsSubscriberTest extends TestCase
{
    public function testGetSubscribedEventsReturnsPostUpdate(): void
    {
        $events = SettingsSubscriber::getSubscribedEvents();

        self::assertArrayHasKey(CrudEvents::POST_UPDATE->value, $events);
        self::assertSame('onPostUpdate', $events[CrudEvents::POST_UPDATE->value]);
    }

    public function testOnPostUpdateCallsInvalidate(): void
    {
        $manager = $this->createMock(SettingsManagerInterface::class);
        $manager->expects(self::once())->method('invalidate');

        $subscriber = new SettingsSubscriber($manager);
        $subscriber->onPostUpdate();
    }
}
