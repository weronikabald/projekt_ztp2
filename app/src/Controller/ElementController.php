<?php
/**
 * Element controller.
 */

namespace App\Controller;

use App\Entity\Element;
use App\Form\ElementType;
use App\Service\ElementService;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ElementController.
 *
 * @Route("/element")
 */
class ElementController extends AbstractController
{
    /**
     * Element service.
     */
    private ElementService $elementService;

    /**
     * ElementController constructor.
     *
     * @param \App\Service\ElementService $elementService Element service
     */
    public function __construct(ElementService $elementService)
    {
        $this->elementService = $elementService;
    }

    /**
     * Index Action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     name="element_index",
     * )
     */
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $page = $request->query->getInt('page', 1);

        $pagination = $this->elementService->createPaginatedList($page, $filters);

        return $this->render(
            'element/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="element_create",
     * )
     *
     * @throws ORMException
     */
    public function create(Request $request): Response
    {
        $element = new Element();
        $form = $this->createForm(ElementType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Element $element Element entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{code}",
     *     methods={"GET"},
     *     name="element_show",
     * )
     */
    public function show(Element $element): Response
    {
        return $this->render(
            'element/show.html.twig',
            ['element' => $element]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Element                       $element Element entity
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
     *     name="element_edit",
     * )
     *
     * @IsGranted("EDIT", subject="element")
     */
    public function edit(Request $request, Element $element): Response
    {
        $form = $this->createForm(ElementType::class, $element, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/edit.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Element                       $element Element entity
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
     *     name="element_delete",
     * )
     *
     * @IsGranted("DELETE", subject="element")
     */
    public function delete(Request $request, Element $element): Response
    {
        $form = $this->createForm(FormType::class, $element, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/delete.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }
}
