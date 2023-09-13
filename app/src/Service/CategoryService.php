<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ElementRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param ElementRepository  $elementRepository  Element repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(CategoryRepository $categoryRepository, ElementRepository $elementRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->elementRepository = $elementRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Find one by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * Get element by category.
     *
     * @param int      $getInt   Get int
     * @param Category $category Category entity
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getElementByCategory(int $getInt, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->elementRepository->queryByCategory($category),
            $getInt,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
