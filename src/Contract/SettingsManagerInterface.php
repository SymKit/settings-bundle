<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Contract;

/**
 * Manager contract for cached settings access and invalidation.
 */
interface SettingsManagerInterface
{
    public function get(): SettingsInterface;

    public function getOrCreateSettings(): SettingsInterface;

    public function invalidate(): void;
}
