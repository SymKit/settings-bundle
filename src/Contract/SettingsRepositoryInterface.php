<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Contract;

/**
 * Repository contract for loading the singleton settings entity.
 */
interface SettingsRepositoryInterface
{
    public function getSettings(): ?SettingsInterface;
}
