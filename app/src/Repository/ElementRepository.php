<?php
/**
 * Element repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Element;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ElementRepository.
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
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

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
     * Save record.
     *
     * @param Element $element Element entity
     *
     * @throws ORMException
     */
    public function save(Element $element): void
    {
        $this->_em->persist($element);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param Element $element Element entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Element $element): void
    {
        $this->_em->remove($element);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @param array $filters Filters array
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial element.{id, title, availability, code, createdAt}',
                'partial category.{id, title}',
                'partial tags.{id, title}',
                'partial authors.{id, name, surname}'
            )
            ->join('element.category', 'category')
            ->leftJoin('element.tags', 'tags')
            ->leftJoin('element.authors', 'authors');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Apply filters to paginated list.
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param array                      $filters      Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(): QueryBuilder
    {
        return null ?? $this->createQueryBuilder('element');
    }
}
