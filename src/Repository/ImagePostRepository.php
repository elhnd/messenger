<?php

namespace App\Repository;

use App\Entity\ImagePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImagePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagePost[]    findAll()
 * @method ImagePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagePost::class);
    }
}
