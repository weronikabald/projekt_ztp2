<?php
/**
 * Author controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\AuthorService;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    /**
     * Author service.
     */
    private AuthorService $authorService;

    /**
     * AuthorController constructor.
     *
     * @param \App\Service\AuthorService $authorService Author service
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Index.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="author_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->authorService->createPaginatedList($page);

        return $this->render(
            'author/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="author_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted ("VIEW", subject="author")
     */
    public function show(Author $author): Response
    {
        return $this->render(
            'author/show.html.twig',
            ['author' => $author]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="author_create",
     * )
     *
     * @throws ORMException
     */
    public function create(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Author                        $author  Author entity
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
     *     name="author_edit",
     * )
     *
     * @IsGranted ("EDIT", subject="author")
     */
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/edit.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Author                        $author  Author entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="author_delete",
     * )
     */
    public function delete(Request $request, Author $author): Response
    {
        $form = $this->createForm(FormType::class, $author, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->delete($author);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/delete.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }
}
