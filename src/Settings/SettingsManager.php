<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Settings;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symkit\SettingsBundle\Contract\SettingsInterface;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Contract\SettingsRepositoryInterface;

final readonly class SettingsManager implements SettingsManagerInterface
{
    private const CACHE_KEY = 'app_settings';
    private const CACHE_TAG = 'app_settings_tag';

    public function __construct(
        private readonly SettingsRepositoryInterface $repository,
        private readonly TagAwareCacheInterface $cache,
        private readonly string $entityClass,
        private readonly int $cacheExpiresAfter,
    ) {
    }

    public function getOrCreateSettings(): SettingsInterface
    {
        $settings = $this->repository->getSettings();

        if (!$settings instanceof SettingsInterface) {
            $settings = new $this->entityClass();
            if (!$settings instanceof SettingsInterface) {
                throw new \LogicException(\sprintf('Configured entity class "%s" must implement %s.', $this->entityClass, SettingsInterface::class));
            }
        }

        return $settings;
    }

    public function get(): SettingsInterface
    {
        return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item): SettingsInterface {
            $item->tag(self::CACHE_TAG);
            $item->expiresAfter($this->cacheExpiresAfter);

            return $this->getOrCreateSettings();
        });
    }

    public function invalidate(): void
    {
        $this->cache->invalidateTags([self::CACHE_TAG]);
    }
}
