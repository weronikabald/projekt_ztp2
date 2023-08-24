<?php
/**
 * UsersData service.
 */

namespace App\Service;

use App\Entity\UsersData;
use App\Repository\UsersDataRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UsersDataService.
 */
class UsersDataService
{
    /**
     * UsersData repository.
     */
    private UsersDataRepository $usersDataRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * UsersDataService constructor.
     *
     * @param \App\Repository\UsersDataRepository     $usersDataRepository UsersData repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator           Paginator
     */
    public function __construct(UsersDataRepository $usersDataRepository, PaginatorInterface $paginator)
    {
        $this->usersDataRepository = $usersDataRepository;
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
            $this->usersDataRepository->queryAll(),
            $page,
            UsersDataRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save usersData.
     *
     * @param \App\Entity\UsersData $usersData UsersData entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UsersData $usersData): void
    {
        $this->usersDataRepository->save($usersData);
    }
}
