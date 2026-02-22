<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symkit\SettingsBundle\Contract\WebManifestGeneratorInterface;

final readonly class WebManifestController
{
    public function __construct(
        private readonly WebManifestGeneratorInterface $webManifestGenerator,
    ) {
    }

    public function __invoke(): Response
    {
        $manifest = $this->webManifestGenerator->generate();

        return new JsonResponse($manifest, 200, [
            'Content-Type' => 'application/manifest+json',
        ]);
    }
}
