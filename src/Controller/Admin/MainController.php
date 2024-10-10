<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/Admin')]
class MainController extends AbstractController
{
    #[Route('', name: 'app_admin_main')]
    public function index(): Response
    {
        return $this->render('admin/main/index.html.twig', [
            'controller_name' => 'mainController',
        ]);
    }
}
