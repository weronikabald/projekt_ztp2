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

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private Security $security;

    /**
     * User service.
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Service\UserService                  $userService User Service
     * @param \Symfony\Component\Security\Core\Security $security    Security
     */
    public function __construct(UserService $userService, Security $security)
    {
        $this->userService = $userService;
        $this->security = $security;
    }

    /**
     * List action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/list",
     *     methods={"GET"},
     *     name="user_list",
     * )
     */
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/list.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show current user.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/show",
     *     methods={"GET"},
     *     name="user_show",
     * )
     */
    public function show(): Response
    {
        $user = $this->security->getUser();

        return $this->render(
            'user/show.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
     * @param \App\Entity\User                          $user            User entity
     * @param UserPasswordEncoderInterface              $passwordEncoder Password Encoder
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
     *     name="user_edit",
     * )
     */
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

        return $this->render(
            'user/edit.html.twig',
            ['form' => $form->createView(),
                'user' => $user, ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\User                          $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit_email",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit_email",
     * )
     */
    public function editEmail(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEmailType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'message_email_changed_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'user/editEmail.html.twig',
            ['form' => $form->createView(),
                'user' => $user, ]
        );
    }
}
