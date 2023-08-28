<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/user')]
class UserController extends AbstractController
{
    private Security $security;
    private UserService $userService;

    public function __construct(UserService $userService, Security $security)
    {
        $this->userService = $userService;
        $this->security = $security;
    }

    #[Route('/list', name: 'user_list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render('user/list.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/show', name: 'user_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->security->getUser();

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'PUT'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->userService->save($user);

            $this->addFlash('success', 'message_password_changed_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    #[Route('/{id}/edit_email', name: 'user_edit_email', methods: ['GET', 'PUT'], requirements: ['id' => '\d+'])]
    public function editEmail(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEmailType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'message_email_changed_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/editEmail.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}

