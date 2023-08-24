<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UsersData;
use App\Form\RegistrationType;
use App\Service\RegistrationService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{

    /**
     * User service.
     */
    private UserService $userService;

    /**
     * Registration service.
     */
    private RegistrationService $registrationService;

    /**
     * RegistrationController constructor.
     *
     * @param \App\Service\RegistrationService $registrationService Registration service
     * @param \App\Service\UserService         $userService         User service
     */
    public function __construct(RegistrationService $registrationService, UserService $userService)
    {
        $this->registrationService = $registrationService;
        $this->userService = $userService;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="app_register",
     * )
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Request $request): Response
    {
        $user = new User();
        $usersData = new UsersData();
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($this->userService->findOneBy($data['email']) !== null) {
                $this->addFlash('danger', 'message_email_already_exists');

                return $this->redirectToRoute('app_register');
            }

            $this->registrationService->register($data, $user, $usersData);
            $this->addFlash('success', 'message_registered_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'registration/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
