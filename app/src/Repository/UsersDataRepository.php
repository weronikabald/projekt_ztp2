<?php
/**
 * Users Data Repository.
 */

namespace App\Repository;

use App\Entity\UsersData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UsersDataRepository.
 *
 * @method UsersData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersData[]    findAll()
 * @method UsersData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersDataRepository extends ServiceEntityRepository
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
     * UsersDataRepository constructor.
     *
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersData::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\UsersData $usersData Users Data entity
     *
     * @throws ORMException
     */
    public function save(UsersData $usersData): void
    {
        $this->_em->persist($usersData);
        $this->_em->flush();
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
                'partial usersData.{id ,user, firstName, lastName}'
            )
            ->orderBy('usersData.lastName', 'ASC');
    }

    /**
     * Get or create new query builder.
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(): QueryBuilder
    {
        return null ?? $this->createQueryBuilder('usersData');
    }
}
