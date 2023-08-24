<?php
/**
 * Users Data controller.
 */

namespace App\Controller;

use App\Entity\UsersData;
use App\Form\UsersDataType;
use App\Service\UsersDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserDataController.
 *
 * @Route("/usersData")
 */
class UsersDataController extends AbstractController
{
    /**
     * UsersData service.
     */
    private UsersDataService $usersDataService;

    /**
     * UsersDataController constructor.
     *
     * @param \App\Service\UsersDataService $usersDataService UsersData service
     */
    public function __construct(UsersDataService $usersDataService)
    {
        $this->usersDataService = $usersDataService;
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP request
     * @param \App\Entity\UsersData                     $usersData Users Data entity
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
     *     name="usersData_edit",
     * )
     */
    public function edit(Request $request, UsersData $usersData): Response
    {
        $form = $this->createForm(UsersDataType::class, $usersData, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersDataService->save($usersData);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'usersData/edit.html.twig',
            [
                'form' => $form->createView(),
                'usersData' => $usersData,
            ]
        );
    }
}
