<?php

namespace App\Repository;

use App\Entity\Opening;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Opening|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opening|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opening[]    findAll()
 * @method Opening[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpeningRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Opening::class);
    }

}
