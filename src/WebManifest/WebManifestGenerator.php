<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\WebManifest;

use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Contract\WebManifestGeneratorInterface;

final readonly class WebManifestGenerator implements WebManifestGeneratorInterface
{
    public function __construct(
        private readonly SettingsManagerInterface $settingsManager,
        private readonly MediaUrlGenerator $mediaUrlGenerator,
    ) {
    }

    public function generate(): array
    {
        $settings = $this->settingsManager->get();

        $icons = [];
        if ($settings->getAndroidIcon192()) {
            $icons[] = [
                'src' => $this->mediaUrlGenerator->generateUrl($settings->getAndroidIcon192()),
                'sizes' => '192x192',
                'type' => $settings->getAndroidIcon192()->getMimeType(),
                'purpose' => 'any maskable',
            ];
        }
        if ($settings->getAndroidIcon512()) {
            $icons[] = [
                'src' => $this->mediaUrlGenerator->generateUrl($settings->getAndroidIcon512()),
                'sizes' => '512x512',
                'type' => $settings->getAndroidIcon512()->getMimeType(),
                'purpose' => 'any maskable',
            ];
        }

        return [
            'name' => $settings->getWebsiteName(),
            'short_name' => $settings->getWebsiteName(),
            'description' => $settings->getWebsiteDescription(),
            'icons' => $icons,
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#ffffff',
        ];
    }
}
