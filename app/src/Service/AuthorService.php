<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorService.
 */
class AuthorService
{
    /**
     * Author repository.
     */
    private AuthorRepository $authorRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * AuthorService constructor.
     *
     * @param \App\Repository\AuthorRepository        $authorRepository Author repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(AuthorRepository $authorRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->authorRepository->queryAll(),
            $page,
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save author.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Author $author): void
    {
        $this->authorRepository->save($author);
    }

    /**
     * Delete author.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Author $author): void
    {
        $this->authorRepository->delete($author);
    }
}
