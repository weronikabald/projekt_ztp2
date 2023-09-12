<?php
/**
 * Reservation repository.
 */

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Element;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ReservationRepository.
 *
 * @extends ServiceEntityRepository<Reservation>
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
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * ReservationRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();

        $queryBuilder
            ->select(
                'partial reservation.{id, comment, email, nickname, createdAt, updatedAt}',
            )
            ->orderBy('reservation.updatedAt', 'DESC');

        return $queryBuilder;
    }

    /**
     * Query reservations by element.
     *
     * @param Element $element Element entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByElement(Element $element): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();

        $queryBuilder
            ->select(
                'partial reservation.{id, comment, email, nickname, createdAt, updatedAt, status}',
            )
            ->where('reservation.element = :element')
            ->setParameter('element', $element)
            ->orderBy('reservation.updatedAt', 'DESC');

        return $queryBuilder;
    }

    /**
     * Save entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function save(Reservation $reservation): void
    {
        $this->_em->persist($reservation);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function delete(Reservation $reservation): void
    {
        $this->_em->remove($reservation);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('reservation');
    }
}
