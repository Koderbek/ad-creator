<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdRepository extends ServiceEntityRepository
{
    /** @var int */
    private const LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * @param array $params
     *
     * @return int|mixed|string
     */
    public function findByFilter(array $params)
    {
        $page = $params['page'] ?? 1;

        $qb = $this->createQueryBuilder('ad');

        $qb->setFirstResult(self::LIMIT * ($page - 1))->setMaxResults(self::LIMIT);

        if (array_key_exists('price', $params)) {
            $qb->orderBy('ad.price', $params['price']);
        }

        if (array_key_exists('createDate', $params)) {
            $qb->orderBy('ad.createDate', $params['createDate']);
        }

        return $qb->getQuery()->getResult();
    }
}