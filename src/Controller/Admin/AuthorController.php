<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Admin/Author')]
class AuthorController extends AbstractController
{
    #[Route('/new', name: 'app_admin_author_new')]
    public function newAuthor(Request $request, AuthorType $formAuthor): Response
    {
        $author = new Author();
        $formAuthor = $this->createForm(AuthorType::class, $author);
        $formAuthor->handleRequest($request);

        return $this->render('admin/author/index.html.twig', [
            'form_author' => $formAuthor->createView(),
        ]);
    }
}
