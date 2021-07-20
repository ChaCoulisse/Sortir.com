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

    public function findAllTrip(){
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->join('trip.participant', 'participant')->addSelect('participant')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
            ->addOrderBy('trip.startHour', 'ASC');
        return $queryBuilder->getQuery()->getResult();
//        dd($queryBuilder->getQuery()->getResult());
    }

    public function findTripByParameter(int $campusId, string $contain, \DateTime $startDate, \DateTime $endDate, int $ownerId, bool $registeredY, bool $registeredN, int $stateId){
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
            if ($registeredY){
                $queryBuilder->setParameter('registered', $registeredY);
            }else{
                $queryBuilder->setParameter('registered', $registeredN);
            }
//        dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();
    }
}
