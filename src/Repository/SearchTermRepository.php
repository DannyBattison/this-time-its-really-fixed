<?php

namespace App\Repository;

use App\Entity\SearchTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SearchTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchTerm[]    findAll()
 * @method SearchTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchTermRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SearchTerm::class);
    }
}
