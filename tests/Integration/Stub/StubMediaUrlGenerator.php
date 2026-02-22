<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Tests\Integration\Stub;

use Symkit\MediaBundle\Entity\Media;

/**
 * Stub for MediaUrlGenerator when MediaBundle is not loaded (e.g. in integration tests).
 */
final class StubMediaUrlGenerator
{
    public function generateUrl(?Media $media): ?string
    {
        return null;
    }
}
