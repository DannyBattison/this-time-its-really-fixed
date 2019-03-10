<?php

namespace App\Repository;

use App\Entity\Commit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Commit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commit[]    findAll()
 * @method Commit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitRepository extends ServiceEntityRepository
{
    const PAGE_SIZE = 50;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Commit::class);
    }

    public function getPagedResults(?int $page)
    {
        if (empty($page)) {
            $page = 1;
        }

        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setFirstResult(($page - 1) * self::PAGE_SIZE)
            ->setMaxResults(self::PAGE_SIZE)
            ->getQuery()
            ->getResult();
    }
}
