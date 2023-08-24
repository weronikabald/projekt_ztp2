<?php
/**
 * Element service.
 */

namespace App\Service;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ElementService.
 */
class ElementService
{
    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private CategoryService $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private TagService $tagService;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * ElementService constructor.
     *
     * @param \App\Repository\ElementRepository       $elementRepository Element repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator         Paginator
     * @param \App\Service\CategoryService            $categoryService   Category service
     * @param \App\Service\TagService                 $tagService        Tag service
     */
    public function __construct(ElementRepository $elementRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->elementRepository = $elementRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->elementRepository->queryAll($filters),
            $page,
            ElementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save element.
     *
     * @param \App\Entity\Element $element Element entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Element $element): void
    {
        $this->elementRepository->save($element);
    }

    /**
     * Delete element.
     *
     * @param \App\Entity\Element $element Element entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Element $element): void
    {
        $this->elementRepository->delete($element);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
