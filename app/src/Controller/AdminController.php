<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_panel')]
    public function adminPanel(): Response
    {
        return $this->render(
            'admin/index.html.twig',
        );
    }
}
