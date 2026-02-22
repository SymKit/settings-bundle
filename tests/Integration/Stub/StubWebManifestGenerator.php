<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Integration\Stub;

use Symkit\SettingsBundle\Contract\WebManifestGeneratorInterface;

final class StubWebManifestGenerator implements WebManifestGeneratorInterface
{
    public function generate(): array
    {
        return [
            'name' => '',
            'short_name' => '',
            'description' => null,
            'icons' => [],
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#ffffff',
        ];
    }
}
