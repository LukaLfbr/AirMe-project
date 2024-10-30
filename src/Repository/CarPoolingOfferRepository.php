<?php

namespace App\Repository;

use App\Entity\CarPoolingOffer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<CarPoolingOffer>
 */
class CarPoolingOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarPoolingOffer::class);
    }

    public function paginateCarPoolingOffers(Request $request, int $page = 1, int $limit = 10): Paginator
{
    $firstResult = ($page - 1) * $limit;

    $query = $this->createQueryBuilder('c')
        ->setFirstResult($firstResult)  
        ->setMaxResults($limit)
        ->getQuery();

    return new Paginator($query, true); 
}
/** @param User $user
 * @return CarPoolingOffer[]  
*/
    public function findCarPoolingOffersByCreator(User $user): array 
    {
        return $this->createQueryBuilder('o')
        ->where('o.creator = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
    }

   // CarPoolingOfferRepository.php
    public function paginateCarPoolingOffersByEvent(int $eventId, int $page = 1, int $limit = 10): Paginator
{
    $firstResult = ($page - 1) * $limit;

    $query = $this->createQueryBuilder('o')
        ->where('o.event = :eventId')
        ->setParameter('eventId', $eventId)
        ->setFirstResult($firstResult)
        ->setMaxResults($limit)
        ->getQuery();

    return new Paginator($query, true);
}



//    /**
//     * @return CarPoolingOffer[] Returns an array of CarPoolingOffer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CarPoolingOffer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
