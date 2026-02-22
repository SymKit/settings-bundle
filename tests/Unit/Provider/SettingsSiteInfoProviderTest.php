<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Provider\SettingsSiteInfoProvider;

final class SettingsSiteInfoProviderTest extends TestCase
{
    public function testGetWebsiteNameReturnsSettingsValueWhenSet(): void
    {
        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getWebsiteName')->willReturn('My Website');

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $mediaUrlGenerator = new MediaUrlGenerator('', '/');
        $translator = $this->createStub(TranslatorInterface::class);

        $provider = new SettingsSiteInfoProvider($manager, $mediaUrlGenerator, $translator);

        self::assertSame('My Website', $provider->getWebsiteName());
    }

    public function testGetWebsiteNameReturnsTranslatedDefaultWhenNull(): void
    {
        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getWebsiteName')->willReturn(null);

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $mediaUrlGenerator = new MediaUrlGenerator('', '/');
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->with('default_website_name', [], 'SymkitSettingsBundle')->willReturn('Default Name');

        $provider = new SettingsSiteInfoProvider($manager, $mediaUrlGenerator, $translator);

        self::assertSame('Default Name', $provider->getWebsiteName());
    }

    public function testGetWebsiteDescriptionReturnsSettingsValue(): void
    {
        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getWebsiteDescription')->willReturn('Description');

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $mediaUrlGenerator = new MediaUrlGenerator('', '/');
        $translator = $this->createStub(TranslatorInterface::class);

        $provider = new SettingsSiteInfoProvider($manager, $mediaUrlGenerator, $translator);

        self::assertSame('Description', $provider->getWebsiteDescription());
    }

    public function testGetDefaultOgImageReturnsGeneratedUrl(): void
    {
        $media = $this->createStub(\Symkit\MediaBundle\Entity\Media::class);
        $media->method('getFilename')->willReturn('og.png');
        $settings = $this->createStub(\Symkit\SettingsBundle\Contract\SettingsInterface::class);
        $settings->method('getOgImage')->willReturn($media);

        $manager = $this->createStub(SettingsManagerInterface::class);
        $manager->method('get')->willReturn($settings);

        $mediaUrlGenerator = new MediaUrlGenerator('', '/media/');
        $translator = $this->createStub(TranslatorInterface::class);

        $provider = new SettingsSiteInfoProvider($manager, $mediaUrlGenerator, $translator);

        self::assertSame('/media/og.png', $provider->getDefaultOgImage());
    }
}
