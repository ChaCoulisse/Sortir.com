<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findAllTrip(){
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
//        ->where('campus.name = :campusName');
//        ->setParameter('campusName', $campus);
            ->addOrderBy('trip.startHour', 'ASC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findPlaceByCity($city_id){

        $queryBuilder= $this->createQueryBuilder('place')
                ->join('place.city_id', 'city')->addSelect('city')
                ->where('place.city_id = :city')
                ->setParameter('city', $city_id)
                ->orderBy('place.name', 'asc');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findTrip(){
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
//        ->where('campus.name = :campusName');
//        ->setParameter('campusName', $campus);
            ->addOrderBy('trip.startHour', 'ASC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function displayTrip($id)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.place', 'place')
            ->join('place.city', 'city')
            ->where('trip.id = :id')
            ->setParameter('id', $id);
//dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();

    }
        public function listParticipantsByTrip($id){
            $queryBuilder = $this->createQueryBuilder('trip')
                ->join('trip.participantsTrip', 'participants')
                ->join('user.participantsTrip', 'participant')
                ->where('trip.id = :id')
                ->setParameter('id', $id);
            dd($queryBuilder->getQuery()->getResult());
        }

    public function updateState($tripId, $stateId)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->where('trip.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->update()
            ->set('trip.state', ':id')
            ->setParameter('id', $stateId);
        return $updateState=$queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}