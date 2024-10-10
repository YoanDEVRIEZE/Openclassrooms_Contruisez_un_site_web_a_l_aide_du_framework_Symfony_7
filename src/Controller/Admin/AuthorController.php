<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
    public function author(Request $request) : Response 
    {
        $dates = [];

        if ($request->query->has('start')) {
            $dates['start'] = $request->query->get('start');
        }

        if ($request->query->has('end')) {
            $dates['end'] = $request->query->get('end');
        }

        $qb = $this->authorRepository->findByDateOfBirth($dates);

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($qb),
        );

        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($currentPage);  
        $pagerfanta->getCurrentPageResults();

        return $this->render('admin/author/index.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }
    
    #[Route('/Show/{id}', name: 'app_admin_author_show',  requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Author $author) : Response 
    { 
        return $this->render('admin/author/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/Edit', name: 'app_admin_author_edit', requirements: ['id' => '\d+'], methods:['GET', 'POST'])]
    public function newAuthor(?Author $author, Request $request): Response
    {
        $author ??= new Author();
        $formAuthor = $this->createForm(AuthorType::class, $author);
        $formAuthor->handleRequest($request);

        if ($formAuthor->isSubmitted() && $formAuthor->isValid()) {
            $this->entityManagerInterface->persist($author);
            $this->entityManagerInterface->flush();

            if ($request->attributes->get('_route') === 'app_admin_author_new') {
                $this->addFlash('success', 'Validation : l\'auteur '. $author->getName() .' a été ajouté avec succès');
                return $this->redirectToRoute('app_admin_author_new');
            }

            $this->addFlash('success', 'Validation : l\'auteur '. $author->getName() .' a été modifié avec succès');
            return $this->redirectToRoute('app_admin_author_show', ['id' => $author->getId()]);            
        } 

        return $this->render('admin/author/index.html.twig', [
            'form_author' => ($formAuthor->createView()) ? $formAuthor : $formAuthor->createView(),
        ]);
    }
}
