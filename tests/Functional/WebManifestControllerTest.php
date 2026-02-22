<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symkit\SettingsBundle\Contract\WebManifestGeneratorInterface;
use Symkit\SettingsBundle\Repository\SettingsRepository;
use Symkit\SettingsBundle\SettingsBundle;
use Symkit\SettingsBundle\Tests\Integration\Stub\StubSettingsEntity;
use Symkit\SettingsBundle\Tests\Integration\Stub\StubWebManifestGenerator;

final class WebManifestControllerTest extends KernelTestCase
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
                'router' => [
                    'resource' => '%kernel.project_dir%/config/routes_webmanifest.yaml',
                ],
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
                'webmanifest' => ['enabled' => true],
                'twig' => ['enabled' => false],
                'metadata_provider' => ['enabled' => false],
                'doctrine' => [
                    'entity' => StubSettingsEntity::class,
                    'repository' => SettingsRepository::class,
                ],
            ]);
            $container->setDefinition(WebManifestGeneratorInterface::class, new Definition(StubWebManifestGenerator::class));
        });
        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testWebManifestRouteReturns200AndValidJson(): void
    {
        self::bootKernel();
        $controller = static::getContainer()->get(\Symkit\SettingsBundle\Controller\Api\WebManifestController::class);
        $response = $controller();

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('application/manifest+json', $response->headers->get('Content-Type') ?? '');

        $content = $response->getContent();
        self::assertNotFalse($content);
        $data = json_decode($content, true);
        self::assertIsArray($data);
        self::assertArrayHasKey('name', $data);
        self::assertArrayHasKey('short_name', $data);
        self::assertArrayHasKey('description', $data);
        self::assertArrayHasKey('icons', $data);
        self::assertArrayHasKey('start_url', $data);
        self::assertArrayHasKey('display', $data);
        self::assertArrayHasKey('background_color', $data);
        self::assertArrayHasKey('theme_color', $data);
    }
}
