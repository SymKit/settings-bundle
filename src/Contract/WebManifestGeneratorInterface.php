<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Contract;

/**
 * Generates site.webmanifest JSON data.
 */
interface WebManifestGeneratorInterface
{
    /**
     * @return array<string, mixed>
     */
    public function generate(): array;
}
