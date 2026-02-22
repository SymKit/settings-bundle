<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Provider;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\MetadataBundle\Contract\SiteInfoProviderInterface;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;

final readonly class SettingsSiteInfoProvider implements SiteInfoProviderInterface
{
    public function __construct(
        private SettingsManagerInterface $settingsManager,
        private MediaUrlGenerator $mediaUrlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function getWebsiteName(): string
    {
        return $this->settingsManager->get()->getWebsiteName()
            ?? $this->translator->trans('default_website_name', [], 'SymkitSettingsBundle');
    }

    public function getWebsiteDescription(): ?string
    {
        return $this->settingsManager->get()->getWebsiteDescription();
    }

    public function getDefaultOgImage(): ?string
    {
        return $this->mediaUrlGenerator->generateUrl($this->settingsManager->get()->getOgImage());
    }

    public function getFavicon(): ?string
    {
        return $this->mediaUrlGenerator->generateUrl($this->settingsManager->get()->getFavicon());
    }

    public function getAppleTouchIcon(): ?string
    {
        return $this->mediaUrlGenerator->generateUrl($this->settingsManager->get()->getAppleTouchIcon());
    }

    public function getAndroidIcon192(): ?string
    {
        return $this->mediaUrlGenerator->generateUrl($this->settingsManager->get()->getAndroidIcon192());
    }

    public function getAndroidIcon512(): ?string
    {
        return $this->mediaUrlGenerator->generateUrl($this->settingsManager->get()->getAndroidIcon512());
    }
}
