<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symkit\MediaBundle\Entity\Media;
use Symkit\SettingsBundle\Contract\SettingsInterface;

#[ORM\Entity(repositoryClass: 'Symkit\SettingsBundle\Repository\SettingsRepository')]
class Settings implements SettingsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore property.unusedType (Doctrine hydrates id; nullable for non-persisted entity) */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $websiteName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $websiteDescription = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Media $websiteLogo = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'og_image_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Media $ogImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialFacebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialInstagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialX = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialGithub = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialYoutube = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialLinkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(groups: ['create', 'edit'])]
    #[Assert\Length(max: 255, groups: ['create', 'edit'])]
    private ?string $socialTiktok = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Media $favicon = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Media $appleTouchIcon = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Media $androidIcon192 = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Media $androidIcon512 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteName(): ?string
    {
        return $this->websiteName;
    }

    public function setWebsiteName(string $websiteName): static
    {
        $this->websiteName = $websiteName;

        return $this;
    }

    public function getWebsiteDescription(): ?string
    {
        return $this->websiteDescription;
    }

    public function setWebsiteDescription(?string $websiteDescription): static
    {
        $this->websiteDescription = $websiteDescription;

        return $this;
    }

    public function getWebsiteLogo(): ?Media
    {
        return $this->websiteLogo;
    }

    public function setWebsiteLogo(?Media $websiteLogo): static
    {
        $this->websiteLogo = $websiteLogo;

        return $this;
    }

    public function getOgImage(): ?Media
    {
        return $this->ogImage;
    }

    public function setOgImage(?Media $ogImage): static
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    public function getSocialFacebook(): ?string
    {
        return $this->socialFacebook;
    }

    public function setSocialFacebook(?string $socialFacebook): static
    {
        $this->socialFacebook = $socialFacebook;

        return $this;
    }

    public function getSocialInstagram(): ?string
    {
        return $this->socialInstagram;
    }

    public function setSocialInstagram(?string $socialInstagram): static
    {
        $this->socialInstagram = $socialInstagram;

        return $this;
    }

    public function getSocialX(): ?string
    {
        return $this->socialX;
    }

    public function setSocialX(?string $socialX): static
    {
        $this->socialX = $socialX;

        return $this;
    }

    public function getSocialGithub(): ?string
    {
        return $this->socialGithub;
    }

    public function setSocialGithub(?string $socialGithub): static
    {
        $this->socialGithub = $socialGithub;

        return $this;
    }

    public function getSocialYoutube(): ?string
    {
        return $this->socialYoutube;
    }

    public function setSocialYoutube(?string $socialYoutube): static
    {
        $this->socialYoutube = $socialYoutube;

        return $this;
    }

    public function getSocialLinkedin(): ?string
    {
        return $this->socialLinkedin;
    }

    public function setSocialLinkedin(?string $socialLinkedin): static
    {
        $this->socialLinkedin = $socialLinkedin;

        return $this;
    }

    public function getSocialTiktok(): ?string
    {
        return $this->socialTiktok;
    }

    public function setSocialTiktok(?string $socialTiktok): static
    {
        $this->socialTiktok = $socialTiktok;

        return $this;
    }

    public function getFavicon(): ?Media
    {
        return $this->favicon;
    }

    public function setFavicon(?Media $favicon): static
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getAppleTouchIcon(): ?Media
    {
        return $this->appleTouchIcon;
    }

    public function setAppleTouchIcon(?Media $appleTouchIcon): static
    {
        $this->appleTouchIcon = $appleTouchIcon;

        return $this;
    }

    public function getAndroidIcon192(): ?Media
    {
        return $this->androidIcon192;
    }

    public function setAndroidIcon192(?Media $androidIcon192): static
    {
        $this->androidIcon192 = $androidIcon192;

        return $this;
    }

    public function getAndroidIcon512(): ?Media
    {
        return $this->androidIcon512;
    }

    public function setAndroidIcon512(?Media $androidIcon512): static
    {
        $this->androidIcon512 = $androidIcon512;

        return $this;
    }

    #[Assert\Callback(groups: ['create', 'edit'])]
    public function validateIcons(ExecutionContextInterface $context, mixed $payload): void
    {
        $allowedImageMimes = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml'];
        $onlyPngMimes = ['image/png'];

        if ($this->favicon && !\in_array($this->favicon->getMimeType(), $allowedImageMimes, true)) {
            $context->buildViolation('validation.favicon_mime')
                ->atPath('favicon')
                ->addViolation()
            ;
        }

        if ($this->appleTouchIcon && !\in_array($this->appleTouchIcon->getMimeType(), $onlyPngMimes, true)) {
            $context->buildViolation('validation.apple_touch_icon_mime')
                ->atPath('appleTouchIcon')
                ->addViolation()
            ;
        }

        if ($this->androidIcon192 && !\in_array($this->androidIcon192->getMimeType(), $onlyPngMimes, true)) {
            $context->buildViolation('validation.android_icon_mime')
                ->atPath('androidIcon192')
                ->addViolation()
            ;
        }

        if ($this->androidIcon512 && !\in_array($this->androidIcon512->getMimeType(), $onlyPngMimes, true)) {
            $context->buildViolation('validation.android_icon_mime')
                ->atPath('androidIcon512')
                ->addViolation()
            ;
        }
    }

    public function __toString(): string
    {
        return 'Settings';
    }
}
