<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symkit\CrudBundle\Enum\CrudEvents;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;

final readonly class SettingsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SettingsManagerInterface $settingsManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CrudEvents::POST_UPDATE->value => 'onPostUpdate',
        ];
    }

    public function onPostUpdate(): void
    {
        $this->settingsManager->invalidate();
    }
}
