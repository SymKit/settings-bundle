<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Contract;

/**
 * Generates site.webmanifest JSON data.
 */
interface WebManifestGeneratorInterface
{
    /**
     * @return array<string, mixed> JSON-compatible manifest (keys: name, short_name, icons, etc.; values: scalar or array)
     */
    public function generate(): array;
}
