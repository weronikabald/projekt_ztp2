<?php
/**
 * Default controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class DefaultController extends AbstractController
{
    #[Route("/", name: "homepage")]
    public function homepage(): Response
    {
        return $this->render("home/index.html.twig");
    }
}
