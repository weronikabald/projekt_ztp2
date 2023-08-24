<?php
/**
 * Reservation controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class ReservationController.
 *
 * @Route("/reservation")
 *
 * @IsGranted("ROLE_USER")
 */
class ReservationController extends AbstractController
{
    /**
     * Security.
     */
    private Security $security;

    /**
     * Reservation service.
     */
    private ReservationService $reservationService;

    /**
     * ReservationController constructor.
     *
     * @param \App\Service\ReservationService           $reservationService Reservation service
     * @param \Symfony\Component\Security\Core\Security $security           Security
     */
    public function __construct(ReservationService $reservationService, Security $security)
    {
        $this->reservationService = $reservationService;
        $this->security = $security;
    }

    /**
     * Reservation list.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/list",
     *     name="reservation_list",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->reservationService->createPaginatedList($page);

        return $this->render(
            'reservation/list.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Reservation by User.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/reservation_user",
     *     name="reservation_user",
     * )
     */
    public function userReservations(Request $request): Response
    {
        $user = $this->security->getUser();
        $page = $request->query->getInt('page', 1);
        $pagination = $this->reservationService->createPaginatedListByUser($page, $user);

        return $this->render(
            'reservation/list.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create new reservation and adjust element availability.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST", "PUT"},
     *     name="reservation_create",
     * )
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->reservationService->createReservation($reservation)) {
                $this->addFlash('success', 'message_created_successfully');
            } else {
                $this->addFlash('danger', 'message_out_of_stock');
            }

            return $this->redirectToRoute('reservation_user');
        }

        return $this->render(
            'reservation/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Return action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request     HTTP request
     * @param \App\Entity\Reservation                   $reservation Element entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/return",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="reservation_return",
     * )
     */
    public function return(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->returnReservation($reservation);
            $this->addFlash('success', 'message_returned_successfully');

            return $this->redirectToRoute('reservation_user');
        }

        return $this->render(
            'reservation/return.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Reservation $reservation Reservation entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="reservation_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("VIEW", subject="reservation")
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render(
            'reservation/show.html.twig',
            ['reservation' => $reservation]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request     HTTP request
     * @param \App\Entity\Reservation                   $reservation Reservation
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="reservation_edit",
     * )
     *
     * @IsGranted ("EDIT", subject="reservation")
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('reservation_user');
        }

        return $this->render(
            'reservation/edit.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
