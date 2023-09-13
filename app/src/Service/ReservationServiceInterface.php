<?php
/**
 * Reservation service interface.
 */

namespace App\Service;

use App\Entity\Reservation;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ReservationServiceInterface.
 */
interface ReservationServiceInterface
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
     * @param Reservation $reservation Reservation entity
     * @param int         $elementId   Element id
     */
    public function save(Reservation $reservation, int $elementId): void;

    /**
     * Delete entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function delete(Reservation $reservation): void;

    /**
     * Update entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function update(Reservation $reservation): void;

    /**
     * Accept reservation.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function accept(Reservation $reservation): void;

    /**
     * Return reservation.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function returnReservation(Reservation $reservation): void;
}
