<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Contract\SettingsRepositoryInterface;
use Symkit\SettingsBundle\Contract\WebManifestGeneratorInterface;
use Symkit\SettingsBundle\Controller\Admin\SettingsController;
use Symkit\SettingsBundle\Controller\Api\WebManifestController;
use Symkit\SettingsBundle\Entity\Settings;
use Symkit\SettingsBundle\EventListener\SettingsSubscriber;
use Symkit\SettingsBundle\Form\SettingsType;
use Symkit\SettingsBundle\Provider\SettingsSiteInfoProvider;
use Symkit\SettingsBundle\Repository\SettingsRepository;
use Symkit\SettingsBundle\Settings\SettingsManager;
use Symkit\SettingsBundle\Twig\SettingsExtension as TwigSettingsExtension;
use Symkit\SettingsBundle\WebManifest\WebManifestGenerator;

class SettingsBundle extends AbstractBundle
{
    protected string $extensionAlias = 'symkit_settings';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->integerNode('cache_expires_after')->defaultValue(86400)->info('Cache expiration time in seconds.')->end()
                ->arrayNode('admin')
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->info('Enable admin controller and routes.')->end()
                        ->scalarNode('route_prefix')->defaultValue('admin')->info('Route prefix for admin (e.g. /admin/settings).')->end()
                    ->end()
                ->end()
                ->arrayNode('webmanifest')
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->info('Enable webmanifest controller and route.')->end()
                    ->end()
                ->end()
                ->arrayNode('twig')
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->info('Enable Twig extension (variable + function).')->end()
                    ->end()
                ->end()
                ->arrayNode('metadata_provider')
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->info('Register SiteInfoProvider for symkit/metadata-bundle.')->end()
                    ->end()
                ->end()
                ->arrayNode('doctrine')
                    ->children()
                        ->scalarNode('entity')->defaultValue(Settings::class)->info('FQCN of settings entity.')->end()
                        ->scalarNode('repository')->defaultValue(SettingsRepository::class)->info('FQCN of settings repository.')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param array{
     *     cache_expires_after?: int,
     *     admin?: array{enabled?: bool, route_prefix?: string},
     *     webmanifest?: array{enabled?: bool},
     *     twig?: array{enabled?: bool},
     *     metadata_provider?: array{enabled?: bool},
     *     doctrine?: array{entity?: string, repository?: string}
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $defaults = [
            'cache_expires_after' => 86400,
            'admin' => ['enabled' => true, 'route_prefix' => 'admin'],
            'webmanifest' => ['enabled' => true],
            'twig' => ['enabled' => true],
            'metadata_provider' => ['enabled' => true],
            'doctrine' => [
                'entity' => Settings::class,
                'repository' => SettingsRepository::class,
            ],
        ];
        $config = array_replace_recursive($defaults, $config);

        $container->parameters()
            ->set('symkit_settings.entity', $config['doctrine']['entity'])
            ->set('symkit_settings.repository', $config['doctrine']['repository'])
            ->set('symkit_settings.admin.route_prefix', $config['admin']['route_prefix'])
        ;

        $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure();

        $services = $container->services();
        $services->set($config['doctrine']['repository'])
            ->arg('$registry', service('doctrine'))
            ->arg('$entityClass', '%symkit_settings.entity%');
        $services->alias(SettingsRepositoryInterface::class, $config['doctrine']['repository'])->public();

        $services->set(SettingsManager::class)
            ->arg('$repository', service(SettingsRepositoryInterface::class))
            ->arg('$cache', service(TagAwareCacheInterface::class))
            ->arg('$entityClass', '%symkit_settings.entity%')
            ->arg('$cacheExpiresAfter', $config['cache_expires_after']);
        $services->alias(SettingsManagerInterface::class, SettingsManager::class)->public();

        $services->set(SettingsType::class)
            ->arg('$entityClass', '%symkit_settings.entity%');

        $services->set(SettingsSubscriber::class);

        if ($config['twig']['enabled']) {
            $services->set(TwigSettingsExtension::class)->tag('twig.extension');
        }

        if ($config['metadata_provider']['enabled']) {
            $services->set(SettingsSiteInfoProvider::class);
        }

        $services->set(WebManifestGenerator::class)
            ->arg('$settingsManager', service(SettingsManagerInterface::class))
            ->arg('$mediaUrlGenerator', service(MediaUrlGenerator::class));
        $services->alias(WebManifestGeneratorInterface::class, WebManifestGenerator::class);

        if ($config['webmanifest']['enabled']) {
            $services->set(WebManifestController::class)
                ->arg('$webManifestGenerator', service(WebManifestGeneratorInterface::class))
                ->tag('controller.service_arguments')
                ->public();
        }

        if ($config['admin']['enabled']) {
            $services->set(SettingsController::class)
                ->arg('$entityClass', '%symkit_settings.entity%')
                ->tag('controller.service_arguments');
        }
    }
}
