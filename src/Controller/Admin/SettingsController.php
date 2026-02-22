<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatableMessage;
use Symkit\CrudBundle\Contract\CrudPersistenceManagerInterface;
use Symkit\CrudBundle\Controller\AbstractCrudController;
use Symkit\MenuBundle\Attribute\ActiveMenu;
use Symkit\MetadataBundle\Attribute\Breadcrumb;
use Symkit\MetadataBundle\Attribute\Seo;
use Symkit\MetadataBundle\Contract\PageContextBuilderInterface;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Form\SettingsType;

#[Seo(title: 'Settings', description: 'Manage application settings.')]
#[Breadcrumb(context: 'admin')]
final class SettingsController extends AbstractCrudController
{
    public function __construct(
        CrudPersistenceManagerInterface $persistenceManager,
        PageContextBuilderInterface $pageContextBuilder,
        private readonly SettingsManagerInterface $settingsManager,
        private readonly string $entityClass,
    ) {
        parent::__construct($persistenceManager, $pageContextBuilder);
    }

    protected function getEntityClass(): string
    {
        return $this->entityClass;
    }

    protected function getFormClass(): string
    {
        return SettingsType::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_settings';
    }

    #[ActiveMenu('admin', 'settings')]
    public function __invoke(Request $request): Response
    {
        $settings = $this->settingsManager->getOrCreateSettings();

        return $this->renderEdit($settings, $request, [
            'page_title' => new TranslatableMessage('page.settings_title', [], 'SymkitSettingsBundle'),
            'page_description' => new TranslatableMessage('page.settings_description', [], 'SymkitSettingsBundle'),
            'redirect_route' => 'admin_settings_index',
            'redirect_params' => [],
            'template_vars' => [
                'show_back' => false,
                'show_delete' => false,
            ],
        ]);
    }
}
