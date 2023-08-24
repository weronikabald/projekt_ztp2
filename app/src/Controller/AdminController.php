<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Homepage.
     *
     * @Route(
     *     "/",
     *     name="admin_panel",
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function adminPanel(): Response
    {
        return $this->render(
            'admin/index.html.twig',
        );
    }
}
