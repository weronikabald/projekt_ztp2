<?php

/**
 * Reservation repository.
 */

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ReservationRepository.
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Save record.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     */
    public function save(Reservation $reservation): void
    {
        $this->_em->persist($reservation);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Reservation $reservation): void
    {
        $this->_em->remove($reservation);
        $this->_em->flush();
    }

    /**
     * Query reservations by author.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('reservation.user = :user')
            ->setParameter('user', $user);

        return $queryBuilder;
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial reservation.{id, rentalDate, returnDate, user}',
                'partial user.{id, email}',
            )
            ->join('reservation.user', 'user')
            ->orderBy('reservation.rentalDate', 'ASC');
    }

    /**
     * Get or create new query builder.
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(): QueryBuilder
    {
        return null ?? $this->createQueryBuilder('reservation');
    }
}
