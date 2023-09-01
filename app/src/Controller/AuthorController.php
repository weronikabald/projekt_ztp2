<?php
/**
 * Author controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Author;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/author')]
class AuthorController extends AbstractController
{
    private Security $security;
    private UserService $userService;

    public function __construct(UserService $userService, Security $security)
    {
        $this->userService = $userService;
        $this->security = $security;
    }

    #[Route('/list', name: 'author_list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render('author/list.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/show', name: 'author_show', methods: ['GET'])]
    public function show(): Response
    {
        $author = $this->security->getUser();

        return $this->render('author/show.html.twig', ['author' => $author]);
    }

    #[Route('/{id}/edit', name: 'author_edit', methods: ['GET', 'PUT'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Author $author, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserPasswordType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($author, $author->getPassword());
            $author->setPassword($password);

            $this->userService->save($author);

            $this->addFlash('success', 'message_password_changed_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('author/edit.html.twig', ['form' => $form->createView(), 'author' => $author]);
    }

    #[Route('/{id}/edit_email', name: 'author_edit_email', methods: ['GET', 'PUT'], requirements: ['id' => '\d+'])]
    public function editEmail(Request $request, Author $author): Response
    {
        $form = $this->createForm(UserEmailType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($author);
            $this->addFlash('success', 'message_email_changed_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('author/editEmail.html.twig', ['form' => $form->createView(), 'author' => $author]);
    }
}
