<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;
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

    public function findAllTrip()
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->join('trip.participant', 'participant')->addSelect('participant')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
            ->addOrderBy('trip.startHour', 'ASC');
        return $queryBuilder->getQuery()->getResult();

    }

    public function findTripByParameter(int $campusId, string $contain, \DateTime $startDate, \DateTime $endDate, int $ownerId, bool $registeredY, bool $registeredN, int $stateId)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->join('trip.participant', 'participant')->addSelect('participant')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
            ->andWhere('trip.campus = :campusId')
            ->setParameter('campusId', $campusId)
            ->andWhere('trip.name = :name')
            ->setParameter('name', $contain)
            ->andWhere('trip.startHour = :start')
            ->setParameter('start', $startDate)
//            ->andWhere('trip.campus = :campus')
//            ->setParameter('campus', $endDate)
            ->andWhere('trip.organizer = :ownerId')
            ->setParameter('ownerId', $ownerId)
            ->andWhere('trip.state = :stateId')
            ->setParameter('stateId', $stateId)
            ->addOrderBy('trip.startHour', 'ASC')
            ->andWhere('trip.participant = :registered');
        if ($registeredY) {
            $queryBuilder->setParameter('registered', $registeredY);
        } else {
            $queryBuilder->setParameter('registered', $registeredN);
        }
//        dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();
    }

    public function findTripFinishedAndCancelled()
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.state', 'state')->addSelect('state')
            ->where('trip.state= :passée')
            ->andWhere('trip.state = :annulée')
            ->setParameter('passée', 'passée')
            ->setParameter('annulée', 'annulée');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findPlaceByCity($city_id)
    {
        $queryBuilder = $this->createQueryBuilder('place')
            ->join('place.city_id', 'city')->addSelect('city')
            ->where('place.city_id = :city')
            ->setParameter('city', $city_id)
            ->orderBy('place.name', 'asc');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findTrip()
    {
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
        return $queryBuilder->getQuery()->getResult();
    }

    public function listParticipantsByTrip($id)
    {
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
        return $queryBuilder->getQuery()->getResult();
    }
}
