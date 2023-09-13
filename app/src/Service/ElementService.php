<?php
/**
 * Element service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ElementService.
 */
class ElementService implements ElementServiceInterface
{
    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * ElementService constructor.
     *
     * @param ElementRepository  $elementRepository Element repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(ElementRepository $elementRepository, PaginatorInterface $paginator)
    {
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
            $this->elementRepository->queryAll(),
            $page,
            ElementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void
    {
        $this->elementRepository->save($element);
    }

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void
    {
        $this->elementRepository->delete($element);
    }

    /**
     * Find one by id.
     *
     * @param int $id Element id
     *
     * @return Element|null Element entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Element
    {
        return $this->elementRepository->findOneById($id);
    }

    /**
     * Can category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Can be deleted?
     *
     * @throws NoResultException|NonUniqueResultException
     */
    public function canBeDeleted(Category $category): bool
    {
        $result = $this->elementRepository->countByCategory($category);

        return !($result > 0);
    }
}
