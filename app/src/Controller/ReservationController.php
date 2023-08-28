<?php
/**
 * Reservation controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route("/reservation")]
class ReservationController extends AbstractController
{
    private Security $security;
    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService, Security $security)
    {
        $this->reservationService = $reservationService;
        $this->security = $security;
    }

    #[Route("/list", name: "reservation_list")]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->reservationService->createPaginatedList($page);

        return $this->render('reservation/list.html.twig', ['pagination' => $pagination]);
    }

    #[Route("/reservation_user", name: "reservation_user")]
    public function userReservations(Request $request): Response
    {
        $user = $this->security->getUser();
        $page = $request->query->getInt('page', 1);
        $pagination = $this->reservationService->createPaginatedListByUser($page, $user);

        return $this->render('reservation/list.html.twig', ['pagination' => $pagination]);
    }

    #[Route("/create", name: "reservation_create", methods: ["GET", "POST", "PUT"])]
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

        return $this->render('reservation/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/{id}/return", name: "reservation_return", methods: ["GET", "DELETE"], requirements: ["id" => "[1-9]\d*"])]
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

        return $this->render('reservation/return.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route("/{id}", name: "reservation_show", methods: ["GET"], requirements: ["id" => "[1-9]\d*"])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', ['reservation' => $reservation]);
    }

    #[Route("/{id}/edit", name: "reservation_edit", methods: ["GET", "PUT"], requirements: ["id" => "[1-9]\d*"])]
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('reservation_user');
        }

        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }
}

