<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\FormBundle\Form\Type\FormSectionType;
use Symkit\MediaBundle\Form\MediaType;

final class SettingsType extends AbstractType
{
    public function __construct(
        private readonly string $entityClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('general', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.general',
                'section_icon' => 'heroicons:information-circle-20-solid',
                'section_description' => 'form.section.general_description',
            ])
                ->add('websiteName', TextType::class, [
                    'label' => 'form.field.website_name',
                    'help' => 'form.help.website_name',
                ])
                ->add('websiteDescription', TextareaType::class, [
                    'label' => 'form.field.website_description',
                    'attr' => ['rows' => 3],
                    'help' => 'form.help.website_description',
                ]),
        );

        $builder->add(
            $builder->create('logos', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.logos',
                'section_icon' => 'heroicons:photo-20-solid',
                'section_description' => 'form.section.logos_description',
            ])
                ->add('websiteLogo', MediaType::class, [
                    'label' => 'form.field.website_logo',
                    'required' => false,
                    'help' => 'form.help.website_logo',
                ])
                ->add('ogImage', MediaType::class, [
                    'label' => 'form.field.og_image',
                    'required' => false,
                    'help' => 'form.help.og_image',
                ]),
        );

        $builder->add(
            $builder->create('social', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.social',
                'section_icon' => 'heroicons:globe-alt-20-solid',
                'section_description' => 'form.section.social_description',
            ])
                ->add('socialFacebook', UrlType::class, [
                    'label' => 'form.field.social_facebook',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_facebook',
                ])
                ->add('socialInstagram', UrlType::class, [
                    'label' => 'form.field.social_instagram',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_instagram',
                ])
                ->add('socialX', UrlType::class, [
                    'label' => 'form.field.social_x',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_x',
                ])
                ->add('socialGithub', UrlType::class, [
                    'label' => 'form.field.social_github',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_github',
                ])
                ->add('socialYoutube', UrlType::class, [
                    'label' => 'form.field.social_youtube',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_youtube',
                ])
                ->add('socialLinkedin', UrlType::class, [
                    'label' => 'form.field.social_linkedin',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_linkedin',
                ])
                ->add('socialTiktok', UrlType::class, [
                    'label' => 'form.field.social_tiktok',
                    'required' => false,
                    'link_button' => true,
                    'help' => 'form.help.social_tiktok',
                ]),
        );

        $builder->add(
            $builder->create('icons', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.icons',
                'section_icon' => 'heroicons:device-phone-mobile-20-solid',
                'section_description' => 'form.section.icons_description',
            ])
                ->add('favicon', MediaType::class, [
                    'label' => 'form.field.favicon',
                    'required' => false,
                    'help' => 'form.help.favicon',
                ])
                ->add('appleTouchIcon', MediaType::class, [
                    'label' => 'form.field.apple_touch_icon',
                    'required' => false,
                    'help' => 'form.help.apple_touch_icon',
                ])
                ->add('androidIcon192', MediaType::class, [
                    'label' => 'form.field.android_icon_192',
                    'required' => false,
                    'help' => 'form.help.android_icon_192',
                ])
                ->add('androidIcon512', MediaType::class, [
                    'label' => 'form.field.android_icon_512',
                    'required' => false,
                    'help' => 'form.help.android_icon_512',
                ]),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->entityClass,
            'translation_domain' => 'SymkitSettingsBundle',
        ]);
    }
}
