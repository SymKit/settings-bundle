<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\Twig;

use PHPUnit\Framework\TestCase;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Tests\Unit\Settings\StubSettings;
use Symkit\SettingsBundle\Twig\SettingsExtension;

final class SettingsExtensionTest extends TestCase
{
    public function testGetGlobalsReturnsSettingsFromManager(): void
    {
        $settings = new StubSettings();
        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $extension = new SettingsExtension($manager);
        $globals = $extension->getGlobals();

        self::assertArrayHasKey('settings', $globals);
        self::assertSame($settings, $globals['settings']);
    }

    public function testGetFunctionsReturnsAppSettingsFunction(): void
    {
        $manager = $this->createStub(SettingsManagerInterface::class);
        $extension = new SettingsExtension($manager);
        $functions = $extension->getFunctions();

        self::assertCount(1, $functions);
        self::assertSame('app_settings', $functions[0]->getName());
    }

    public function testGetSettingsReturnsSameAsManagerGet(): void
    {
        $settings = new StubSettings();
        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $extension = new SettingsExtension($manager);

        self::assertSame($settings, $extension->getSettings());
    }
}
