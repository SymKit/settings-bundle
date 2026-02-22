<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Integration\Stub;

use Doctrine\ORM\Mapping as ORM;
use Symkit\MediaBundle\Entity\Media;
use Symkit\SettingsBundle\Contract\SettingsInterface;

/**
 * Minimal Doctrine entity for integration tests (no Media relations).
 */
#[ORM\Entity]
#[ORM\Table(name: 'stub_settings')]
class StubSettingsEntity implements SettingsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $websiteName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $websiteDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteName(): ?string
    {
        return $this->websiteName;
    }

    public function getWebsiteDescription(): ?string
    {
        return $this->websiteDescription;
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
