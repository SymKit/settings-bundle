<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Unit\Settings;

use Symkit\MediaBundle\Entity\Media;
use Symkit\SettingsBundle\Contract\SettingsInterface;

final class StubSettings implements SettingsInterface
{
    public function getId(): ?int
    {
        return null;
    }

    public function getWebsiteName(): ?string
    {
        return null;
    }

    public function getWebsiteDescription(): ?string
    {
        return null;
    }

    public function getWebsiteLogo(): ?Media
    {
        return null;
    }

    public function getOgImage(): ?Media
    {
        return null;
    }

    public function getSocialFacebook(): ?string
    {
        return null;
    }

    public function getSocialInstagram(): ?string
    {
        return null;
    }

    public function getSocialX(): ?string
    {
        return null;
    }

    public function getSocialGithub(): ?string
    {
        return null;
    }

    public function getSocialYoutube(): ?string
    {
        return null;
    }

    public function getSocialLinkedin(): ?string
    {
        return null;
    }

    public function getSocialTiktok(): ?string
    {
        return null;
    }

    public function getFavicon(): ?Media
    {
        return null;
    }

    public function getAppleTouchIcon(): ?Media
    {
        return null;
    }

    public function getAndroidIcon192(): ?Media
    {
        return null;
    }

    public function getAndroidIcon512(): ?Media
    {
        return null;
    }
}
