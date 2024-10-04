<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Admin/Author')]
class AuthorController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private AuthorRepository $authorRepository;

    public function __construct(EntityManagerInterface $entityManagerInterface, AuthorRepository $authorRepository) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->authorRepository = $authorRepository;
    }

    #[Route('', name: 'app_admin_author')]
    public function author() : Response 
    {
        return $this->render('admin/author/index.html.twig', [
            'liste_author' => $this->authorRepository->findAll(),
        ]);
    }
    
    #[Route('/Show/{id}', name: 'app_admin_author_show')]
    public function show($id) : Response 
    { 
        return $this->render('admin/author/show.html.twig', [
            'author' => $this->authorRepository->find($id),
        ]);
    }

    #[Route('/new', name: 'app_admin_author_new')]
    public function newAuthor(Request $request): Response
    {
        $author = new Author();
        $formAuthor = $this->createForm(AuthorType::class, $author);
        $formAuthor->handleRequest($request);

        if ($formAuthor->isSubmitted() && $formAuthor->isValid()) {
            $this->entityManagerInterface->persist($author);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'Validation : l\'auteur '. $author->getName() .' a été ajouté avec succès');
            return $this->redirectToRoute('app_admin_author_new');
        } 

        return $this->render('admin/author/index.html.twig', [
            'form_author' => ($formAuthor->createView()) ? $formAuthor : $formAuthor->createView(),
        ]);
    }
}
