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
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;
use Symkit\SettingsBundle\Contract\SettingsRepositoryInterface;
use Symkit\SettingsBundle\Repository\SettingsRepository;
use Symkit\SettingsBundle\SettingsBundle;
use Symkit\SettingsBundle\Tests\Integration\Stub\StubSettingsEntity;

final class BundleBootTest extends KernelTestCase
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
        $kernel->addTestConfig(static function (ContainerBuilder $container): void {
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
                    'enable_native_lazy_objects' => true,
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
            $container->register(TagAwareCacheInterface::class, TagAwareAdapter::class)
            ->addArgument(new Reference('cache.app'));
            $container->loadFromExtension('symkit_settings', [
                'admin' => ['enabled' => false],
                'webmanifest' => ['enabled' => false],
                'twig' => ['enabled' => false],
                'metadata_provider' => ['enabled' => false],
                'doctrine' => [
                    'entity' => StubSettingsEntity::class,
                    'repository' => SettingsRepository::class,
                ],
            ]);
        });
        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testBundleBootsWithoutError(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        self::assertNotNull($container->get(SettingsManagerInterface::class));
        self::assertNotNull($container->get(SettingsRepositoryInterface::class));
    }

    public function testSettingsManagerCanBeRetrieved(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $em = $container->get(\Doctrine\ORM\EntityManagerInterface::class);
        (new \Doctrine\ORM\Tools\SchemaTool($em))->createSchema($em->getMetadataFactory()->getAllMetadata());
        $manager = $container->get(SettingsManagerInterface::class);
        self::assertNotNull($manager->getOrCreateSettings());
    }
}
