<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Twig;

use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

final class SettingsExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly SettingsManagerInterface $settingsManager,
    ) {
    }

    public function getGlobals(): array
    {
        return [
            'settings' => $this->settingsManager->get(),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_settings', [$this, 'getSettings']),
        ];
    }

    public function getSettings(): object
    {
        return $this->settingsManager->get();
    }
}
