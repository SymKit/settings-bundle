<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\Settings;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symkit\SettingsBundle\Contract\SettingsInterface;
use Symkit\SettingsBundle\Contract\SettingsRepositoryInterface;
use Symkit\SettingsBundle\Settings\SettingsManager;

final class SettingsManagerTest extends TestCase
{
    public function testGetDelegatesToCacheAndCallsGetOrCreateSettingsInCallback(): void
    {
        $settings = $this->createStub(SettingsInterface::class);
        $repository = $this->createStub(SettingsRepositoryInterface::class);
        $repository->method('getSettings')->willReturn($settings);

        $cache = $this->createMock(TagAwareCacheInterface::class);
        $cache->expects(self::once())
            ->method('get')
            ->with('app_settings', self::anything())
            ->willReturnCallback(function (string $key, callable $callback) {
                $item = $this->createMock(ItemInterface::class);
                $item->expects(self::once())->method('tag')->with('app_settings_tag');
                $item->expects(self::once())->method('expiresAfter')->with(86400);

                return $callback($item);
            });

        $manager = new SettingsManager($repository, $cache, SettingsInterface::class, 86400);

        self::assertSame($settings, $manager->get());
    }

    public function testGetOrCreateSettingsReturnsExistingWhenRepositoryReturnsSettings(): void
    {
        $settings = $this->createStub(SettingsInterface::class);
        $repository = $this->createStub(SettingsRepositoryInterface::class);
        $repository->method('getSettings')->willReturn($settings);
        $cache = $this->createStub(TagAwareCacheInterface::class);

        $manager = new SettingsManager($repository, $cache, SettingsInterface::class, 86400);

        self::assertSame($settings, $manager->getOrCreateSettings());
    }

    public function testGetOrCreateSettingsCreatesNewInstanceWhenRepositoryReturnsNull(): void
    {
        $repository = $this->createStub(SettingsRepositoryInterface::class);
        $repository->method('getSettings')->willReturn(null);
        $cache = $this->createStub(TagAwareCacheInterface::class);

        $manager = new SettingsManager($repository, $cache, StubSettings::class, 86400);

        $result = $manager->getOrCreateSettings();
        self::assertInstanceOf(SettingsInterface::class, $result);
        self::assertInstanceOf(StubSettings::class, $result);
    }

    public function testGetOrCreateSettingsThrowsWhenConfiguredEntityDoesNotImplementInterface(): void
    {
        $repository = $this->createStub(SettingsRepositoryInterface::class);
        $repository->method('getSettings')->willReturn(null);
        $cache = $this->createStub(TagAwareCacheInterface::class);

        $manager = new SettingsManager($repository, $cache, \stdClass::class, 86400);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('must implement');
        $manager->getOrCreateSettings();
    }

    public function testInvalidateCallsCacheInvalidateTags(): void
    {
        $repository = $this->createStub(SettingsRepositoryInterface::class);
        $cache = $this->createMock(TagAwareCacheInterface::class);
        $cache->expects(self::once())
            ->method('invalidateTags')
            ->with(['app_settings_tag']);

        $manager = new SettingsManager($repository, $cache, SettingsInterface::class, 86400);
        $manager->invalidate();
    }
}
