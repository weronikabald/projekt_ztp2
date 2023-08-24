<?php
/**
 * Reservation service.
 */

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ElementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use DateTime;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class ReservationService.
 */
class ReservationService
{
    /**
     * Security.
     */
    private Security $security;

    /**
     * Reservation repository.
     */
    private ReservationRepository $reservationRepository;

    /**
     * User repository.
     */
    private UserRepository $userRepository;

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
     * @param \App\Repository\ReservationRepository     $reservationRepository Reservation Repository
     * @param \App\Repository\ElementRepository         $elementRepository     Element Repository
     * @param \App\Repository\UserRepository            $userRepository        User Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator             Paginator Interface
     * @param \Symfony\Component\Security\Core\Security $security              Security
     */
    public function __construct(ReservationRepository $reservationRepository, ElementRepository $elementRepository, UserRepository $userRepository, PaginatorInterface $paginator, Security $security)
    {
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
        $this->elementRepository = $elementRepository;
        $this->paginator = $paginator;
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
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
            $this->reservationRepository->queryAll(),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Create paginated list by User.
     *
     * @param int              $page Page number
     * @param \App\Entity\User $user User Entity
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedListByUser(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->reservationRepository->queryByAuthor($user),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save reservation.
     *
     * @param \App\Entity\Reservation $reservation Reservation entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation);
    }

    /**
     * Delete reservation.
     *
     * @param \App\Entity\Reservation $reservation Reservation entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Reservation $reservation): void
    {
        $this->reservationRepository->delete($reservation);
    }

    /**
     * Create reservation.
     *
     * @param \App\Entity\Reservation $reservation Reservation
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\ORMException
     *
     */
    public function createReservation(Reservation $reservation): bool
    {
        $element = $reservation->getElement();
        $user = $this->security->getUser();
        $reservation->setUser($user);
        $reservation->setReturnDate(new DateTime('+1 month'));
        $currentAvailability = $element->getAvailability();

        if ($currentAvailability > 0) {
            $currentAvailability = $currentAvailability - 1;
            $element->setAvailability($currentAvailability);
            $this->elementRepository->save($element);
            $this->reservationRepository->save($reservation);

            return true;
        }

        return false;
    }

    /**
     * Return reservation.
     *
     * @param \App\Entity\Reservation $reservation Reservation
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function returnReservation(Reservation $reservation): void
    {
        $element = $reservation->getElement();
        $currentAvailability = $element->getAvailability();
        $currentAvailability = $currentAvailability + 1;
        $element->setAvailability($currentAvailability);

        $this->elementRepository->save($element);
        $this->reservationRepository->delete($reservation);
    }
}
