<?php
/**
 * Default controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 *
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * Homepage.
     *
     * @Route(
     *     "/",
     *     name="homepage",
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function homepage(): Response
    {
        return $this->render(
            'home/index.html.twig',
        );
    }
}
