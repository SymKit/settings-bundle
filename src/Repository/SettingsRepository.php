<?php

declare(strict_types=1);

namespace Symkit\SettingsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symkit\SettingsBundle\Contract\SettingsInterface;
use Symkit\SettingsBundle\Contract\SettingsRepositoryInterface;
use Symkit\SettingsBundle\Entity\Settings;

/**
 * @extends ServiceEntityRepository<Settings>
 */
final class SettingsRepository extends ServiceEntityRepository implements SettingsRepositoryInterface
{
    /**
     * @param class-string<SettingsInterface> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Settings::class)
    {
        // @phpstan-ignore-next-line argument.type (config allows custom entity implementing SettingsInterface)
        parent::__construct($registry, $entityClass);
    }

    public function getSettings(): ?SettingsInterface
    {
        return $this->findOneBy([]);
    }
}
