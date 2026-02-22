<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Integration;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symkit\MediaBundle\Service\MediaUrlGenerator;
use Symkit\SettingsBundle\Controller\Api\WebManifestController;
use Symkit\SettingsBundle\Repository\SettingsRepository;
use Symkit\SettingsBundle\SettingsBundle;
use Symkit\SettingsBundle\Tests\Integration\Stub\StubMediaUrlGenerator;
use Symkit\SettingsBundle\Tests\Integration\Stub\StubSettingsEntity;

final class ConfigTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        restore_exception_handler();
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        /** @var TestKernel $kernel */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(FrameworkBundle::class);
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(SettingsBundle::class);
        $config = $options['symkit_config'] ?? [
            'admin' => ['enabled' => false],
            'webmanifest' => ['enabled' => false],
            'twig' => ['enabled' => false],
            'metadata_provider' => ['enabled' => false],
        ];
        $kernel->addTestConfig(static function (ContainerBuilder $container) use ($config): void {
            $container->loadFromExtension('framework', [
                'secret' => 'test',
                'test' => true,
                'http_method_override' => false,
                'cache' => ['app' => 'cache.adapter.array'],
                'asset_mapper' => ['enabled' => false],
            ]);
            $container->loadFromExtension('doctrine', [
                'dbal' => ['url' => 'sqlite:///:memory:'],
                'orm' => [
                    'auto_generate_proxy_classes' => true,
                    'enable_native_lazy_objects' => \PHP_VERSION_ID >= 80400,
                    'mappings' => [
                        'Stub' => [
                            'type' => 'attribute',
                            'is_bundle' => false,
                            'dir' => '%kernel.project_dir%/tests/Integration/Stub',
                            'prefix' => 'Symkit\SettingsBundle\Tests\Integration\Stub',
                        ],
                    ],
                ],
            ]);
            $config['doctrine'] ??= [
                'entity' => StubSettingsEntity::class,
                'repository' => SettingsRepository::class,
            ];
            $container->register(TagAwareCacheInterface::class, TagAwareAdapter::class)
                ->addArgument(new Reference('cache.app'));
            $container->register(MediaUrlGenerator::class, StubMediaUrlGenerator::class);
            $container->loadFromExtension('symkit_settings', $config);
        });
        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testWebManifestControllerRegisteredWhenEnabled(): void
    {
        self::bootKernel([
            'symkit_config' => [
                'admin' => ['enabled' => false],
                'webmanifest' => ['enabled' => true],
                'twig' => ['enabled' => false],
                'metadata_provider' => ['enabled' => false],
            ],
        ]);
        self::assertTrue(static::getContainer()->has(WebManifestController::class));
    }

    public function testWebManifestControllerNotRegisteredWhenDisabled(): void
    {
        self::bootKernel([
            'symkit_config' => [
                'admin' => ['enabled' => false],
                'webmanifest' => ['enabled' => false],
                'twig' => ['enabled' => false],
                'metadata_provider' => ['enabled' => false],
            ],
        ]);
        self::assertFalse(static::getContainer()->has(WebManifestController::class));
    }

    public function testDefaultConfigRegistersWebManifestController(): void
    {
        self::bootKernel([
            'symkit_config' => [
                'admin' => ['enabled' => false],
                'twig' => ['enabled' => false],
                'metadata_provider' => ['enabled' => false],
                'doctrine' => [
                    'entity' => StubSettingsEntity::class,
                    'repository' => SettingsRepository::class,
                ],
            ],
        ]);
        self::assertTrue(static::getContainer()->has(WebManifestController::class));
    }
}
