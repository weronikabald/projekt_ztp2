<?php
/**
 * Reservation service interface.
 */

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\ElementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Interface ReservationServiceInterface.
 */
class ReservationService implements ReservationServiceInterface
{
    /**
     * Reservation repository.
     */
    private ReservationRepository $reservationRepository;

    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * ReservationService constructor.
     *
     * @param ReservationRepository $reservationRepository Reservation repository
     * @param PaginatorInterface    $paginator             Paginator
     * @param ElementRepository     $elementRepository     Element repository
     */
    public function __construct(ReservationRepository $reservationRepository, PaginatorInterface $paginator, ElementRepository $elementRepository)
    {
        $this->reservationRepository = $reservationRepository;
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
            $this->reservationRepository->queryAll(),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Reservation $reservation Reservation entity
     * @param int         $elementId   Element id
     */
    public function save(Reservation $reservation, int $elementId): void
    {
        $element = $this->elementRepository->findOneBy(['id' => $elementId]);
        $reservation->setElement($element);
        $reservation->setStatus('new');
        $this->reservationRepository->save($reservation);
    }

    /**
     * Delete entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function delete(Reservation $reservation): void
    {
        $this->reservationRepository->delete($reservation);
    }

    /**
     * Update entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function update(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation);
    }

    /**
     * Accept reservation.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function accept(Reservation $reservation): void
    {
        $stock = $reservation->getElement()->getStock();
        if ($stock <= 0) {
            $reservation->setStatus('out_of_stock');
            $this->reservationRepository->save($reservation);

            return;
        }
        $reservation->setStatus('accepted');
        $element = $reservation->getElement();
        $elementStock = $element->getStock();
        $element->setStock($elementStock - 1);
        $this->reservationRepository->save($reservation);
    }

    /**
     * Return reservation.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function returnReservation(Reservation $reservation): void
    {
        $reservation->setStatus('returned');
        $element = $reservation->getElement();
        $elementStock = $element->getStock();
        $element->setStock($elementStock + 1);
        $this->reservationRepository->save($reservation);
    }
}
