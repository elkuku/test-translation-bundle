<?php

namespace App\Repository;

use App\Entity\Translation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Translation>
 */
class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Translation::class);
    }

    public function findByObjectAndObjectIds(string $objectType, array $objectIds)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.objectType = :objectType')
            ->andWhere('t.objectId IN (:objectIds)')
            ->setParameter('objectType', $objectType)
            ->setParameter('objectIds', $objectIds)
            ->getQuery()
            ->getResult()
        ;
    }
}
