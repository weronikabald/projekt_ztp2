<?php
/**
 * Category controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category')]
class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->categoryService->createPaginatedList($page);

        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'], requirements: ['id' => '[1-9]\d*'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', ['category' => $category]);
    }

    #[Route('/create', name: 'category_create', methods: ['GET', 'POST', 'PUT'])]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET', 'PUT'], requirements: ['id' => '[1-9]\d*'])]
    #[IsGranted("EDIT", subject: "category")]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', ['form' => $form->createView(), 'category' => $category]);
    }

    #[Route('/{id}/delete', name: 'category_delete', methods: ['GET', 'DELETE'], requirements: ['id' => '[1-9]\d*'])]
    #[IsGranted("DELETE", subject: "category")]
    public function delete(Request $request, Category $category): Response
    {
        if ($category->getElements()->count()) {
            $this->addFlash('warning', 'message_category_contains_elements');

            return $this->redirectToRoute('category_index');
        }

        $form = $this->createForm(FormType::class, $category, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/delete.html.twig', ['form' => $form->createView(), 'category' => $category]);
    }
}