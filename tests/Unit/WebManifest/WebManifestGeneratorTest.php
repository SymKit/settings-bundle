<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\WebManifest;

use PHPUnit\Framework\TestCase;
use Symkit\MediaBundle\Entity\Media;
use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\WebManifest\WebManifestGenerator;

final class WebManifestGeneratorTest extends TestCase
{
    public function testGenerateReturnsExpectedStructure(): void
    {
        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getWebsiteName')->willReturn('My Site');
        $settings->method('getWebsiteDescription')->willReturn('My description');
        $settings->method('getAndroidIcon192')->willReturn(null);
        $settings->method('getAndroidIcon512')->willReturn(null);

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $mediaUrlGenerator = new MediaUrlGenerator('/public', '/media/');

        $generator = new WebManifestGenerator($manager, $mediaUrlGenerator);
        $result = $generator->generate();

        self::assertSame('My Site', $result['name']);
        self::assertSame('My Site', $result['short_name']);
        self::assertSame('My description', $result['description']);
        self::assertSame([], $result['icons']);
        self::assertSame('/', $result['start_url']);
        self::assertSame('standalone', $result['display']);
        self::assertSame('#ffffff', $result['background_color']);
        self::assertSame('#ffffff', $result['theme_color']);
    }

    public function testGenerateIncludesIconsWhenSet(): void
    {
        $icon192 = $this->createStub(Media::class);
        $icon192->method('getMimeType')->willReturn('image/png');
        $icon512 = $this->createStub(Media::class);
        $icon512->method('getMimeType')->willReturn('image/png');

        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getWebsiteName')->willReturn('Site');
        $settings->method('getWebsiteDescription')->willReturn(null);
        $settings->method('getAndroidIcon192')->willReturn($icon192);
        $settings->method('getAndroidIcon512')->willReturn($icon512);

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $icon192->method('getFilename')->willReturn('icon192.png');
        $icon512->method('getFilename')->willReturn('icon512.png');
        $mediaUrlGenerator = new MediaUrlGenerator('/public', '/media/');

        $generator = new WebManifestGenerator($manager, $mediaUrlGenerator);
        $result = $generator->generate();

        self::assertCount(2, $result['icons']);
        self::assertSame('/media/icon192.png', $result['icons'][0]['src']);
        self::assertSame('192x192', $result['icons'][0]['sizes']);
        self::assertSame('image/png', $result['icons'][0]['type']);
        self::assertSame('/media/icon512.png', $result['icons'][1]['src']);
        self::assertSame('512x512', $result['icons'][1]['sizes']);
    }
}
