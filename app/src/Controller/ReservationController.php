<?php
/**
 * Reservation controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\Type\ReservationType;
use App\Service\ReservationServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReservationController.
 */
#[Route('/reservation')]
class ReservationController extends AbstractController
{
    /**
     * Reservation service.
     */
    private ReservationServiceInterface $reservationService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * ReservationController constructor.
     *
     * @param ReservationServiceInterface $reservationService Reservation service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(ReservationServiceInterface $reservationService, TranslatorInterface $translator)
    {
        $this->reservationService = $reservationService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'reservation_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->reservationService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'reservation/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'reservation_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Reservation $reservation): Response
    {
        return $this->render(
            'reservation/show.html.twig',
            ['reservation' => $reservation]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create/',
        name: 'reservation_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $elementId = $request->query->getInt('id');

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation, $elementId);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('element_show', ['id' => $elementId]);
        }

        return $this->render(
            'reservation/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Accept action.
     *
     * @param Request $request HTTP request
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/accept',
        name: 'reservation_accept',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    #[IsGranted('ACCEPT')]
    public function accept(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, [
            'method' => 'PUT',
            'action' => $this->generateUrl(
                'reservation_accept',
                ['id' => $reservation->getId()]
            ),
        ]);

        $element = $reservation->getElement();
        $elementStock = $element->getStock();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $elementStock > 0) {
            $this->reservationService->accept($reservation);

            $this->addFlash(
                'success',
                $this->translator->trans('message.accepted_successfully')
            );

            return $this->redirectToRoute('reservation_index');
        }
        if ($elementStock <= 0) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.out_of_stock')
            );

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/accept.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Return action.
     *
     * @param Request $request HTTP request
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/return',
        name: 'reservation_return',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    #[IsGranted('RETURN')]
    public function returnReservation(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, [
            'method' => 'PUT',
            'action' => $this->generateUrl(
                'reservation_return',
                ['id' => $reservation->getId()]
            ),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->returnReservation($reservation);

            $this->addFlash(
                'success',
                $this->translator->trans('message.returned_successfully')
            );

            return $this->redirectToRoute('reservation_index');
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
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/delete',
        name: 'reservation_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE',
    )]
    #[IsGranted('DELETE')]
    public function delete(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, [
            'method' => 'DELETE',
            'action' => $this->generateUrl(
                'reservation_delete',
                ['id' => $reservation->getId()]
            ),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->delete($reservation);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
