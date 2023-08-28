<?php
/**
 * Element controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Element;
use App\Form\ElementType;
use App\Service\ElementService;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

#[Route('/element')]
class ElementController extends AbstractController
{
    private ElementService $elementService;

    public function __construct(ElementService $elementService)
    {
        $this->elementService = $elementService;
    }

    #[Route('/', name: 'element_index')]
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $page = $request->query->getInt('page', 1);

        $pagination = $this->elementService->createPaginatedList($page, $filters);

        return $this->render('element/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/create', name: 'element_create', methods: ['GET', 'POST'])]
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

        return $this->render('element/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{code}', name: 'element_show')]
    public function show(Element $element): Response
    {
        return $this->render('element/show.html.twig', ['element' => $element]);
    }

    #[Route('/{id}/edit', name: 'element_edit', methods: ['GET', 'PUT'], requirements: ['id' => '[1-9]\d*'])]
    #[IsGranted("EDIT", subject: "element")]
    public function edit(Request $request, Element $element): Response
    {
        $form = $this->createForm(ElementType::class, $element, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('element_index');
        }

        return $this->render('element/edit.html.twig', ['form' => $form->createView(), 'element' => $element]);
    }

    #[Route('/{id}/delete', name: 'element_delete', methods: ['GET', 'DELETE'], requirements: ['id' => '[1-9]\d*'])]
    #[IsGranted("DELETE", subject: "element")]
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

        return $this->render('element/delete.html.twig', ['form' => $form->createView(), 'element' => $element]);
    }
}

