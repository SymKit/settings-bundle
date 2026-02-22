<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Contract;

use Symkit\MediaBundle\Entity\Media;

/**
 * Contract for the settings entity used by Provider and WebManifestGenerator.
 * Custom entities must implement this interface.
 */
interface SettingsInterface
{
    public function getId(): ?int;

    public function getWebsiteName(): ?string;

    public function getWebsiteDescription(): ?string;

    public function getWebsiteLogo(): ?Media;

    public function getOgImage(): ?Media;

    public function getSocialFacebook(): ?string;

    public function getSocialInstagram(): ?string;

    public function getSocialX(): ?string;

    public function getSocialGithub(): ?string;

    public function getSocialYoutube(): ?string;

    public function getSocialLinkedin(): ?string;

    public function getSocialTiktok(): ?string;

    public function getFavicon(): ?Media;

    public function getAppleTouchIcon(): ?Media;

    public function getAndroidIcon192(): ?Media;

    public function getAndroidIcon512(): ?Media;
}
