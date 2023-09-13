<?php
/**
 * Element repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Element;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ElementRepository.
 *
 * @extends ServiceEntityRepository<Element>
 *
 * @method Element|null find($id, $lockMode = null, $lockVersion = null)
 * @method Element|null findOneBy(array $criteria, array $orderBy = null)
 * @method Element[]    findAll()
 * @method Element[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementRepository extends ServiceEntityRepository
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
     * ElementRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Element::class);
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
                'partial element.{id, title, createdAt, updatedAt}',
            )
            ->orderBy('element.updatedAt', 'DESC');

        return $queryBuilder;
    }

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void
    {
        $this->_em->persist($element);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void
    {
        $this->_em->remove($element);
        $this->_em->flush();
    }

    /**
     * Query by element.
     *
     * @param int $id Element id
     *
     * @return Element|null Query builder
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Element
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();

        $queryBuilder
            ->select(
                'partial element.{id, title, createdAt, updatedAt}',
            )
            ->where('element.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Query all records by category.
     *
     * @param Category $category Category entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByCategory(Category $category): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();

        $queryBuilder
            ->select(
                'partial element.{id, title, createdAt, updatedAt, stock}',
                'partial category.{id, title}',
            )
            ->join('element.category', 'category')
            ->where('element.category = :category')
            ->setParameter(':category', $category)
            ->orderBy('element.updatedAt', 'DESC');

        return $queryBuilder;
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
        return $queryBuilder ?? $this->createQueryBuilder('element');
    }

    /**
     * Count elements by category.
     *
     * @param Category $category Category
     *
     * @return int Number of elements in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByCategory(Category $category): int
    {
        $queryBuilder = $this->createQueryBuilder('element');

        return $queryBuilder->select($queryBuilder->expr()->count('element'))
            ->where('element.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
