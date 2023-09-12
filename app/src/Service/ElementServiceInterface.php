<?php
/**
 * Element service interface.
 */

namespace App\Service;

use App\Entity\Element;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ElementServiceInterface.
 */
interface ElementServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void;

    /**
     * Find one by id.
     *
     * @param int $id Element id
     *
     * @return Element|null Element entity
     */
    public function findOneById(int $id): ?Element;
}
