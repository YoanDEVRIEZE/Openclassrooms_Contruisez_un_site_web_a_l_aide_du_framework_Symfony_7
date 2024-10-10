<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Admin/Book')]
class BookController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private BookRepository $bookRepository;

    Public function __construct(EntityManagerInterface $entityManagerInterface, BookRepository $bookRepository) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->bookRepository = $bookRepository;
    }

    #[Route('', name: 'app_admin_book')]
    public function book(Request $request) : Response
    {
        $qb = $this->bookRepository->findListeBook();

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($qb),
        );
        
        $pagerfanta->setMaxPerPage(3);

        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->getCurrentPageResults();

        return $this->render('admin/book/index.html.twig', [
            'liste_book' => $pagerfanta,
        ]);
    }

    #[Route('/{id}/Show', name: 'app_admin_book_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showBook(Book $book) : Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $this->bookRepository->find($book),
        ]);
    }

    #[Route('/New', name: 'app_admin_book_new')]
    #[Route('/{id}/Edit', name: 'app_admin_book_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function newBook(?Book $book, Request $request): Response
    {
        $book ??= new Book();
        $formBook = $this->createForm(BookType::class, $book);
        $formBook->handleRequest($request);

        if ($formBook->isSubmitted() && $formBook->isValid()) {
            $this->entityManagerInterface->persist($book);
            $this->entityManagerInterface->flush();

            if ($request->attributes->get('_route') === 'app_admin_book_new') {
                $this->addFlash('success', 'Validation : le livre '.$book->getTitle().' a été ajouté avec succès');
                return $this->redirectToRoute('app_admin_book_new');
            }
            
            $this->addFlash('success', 'Validation : le livre '.$book->getTitle().' a été modifié avec succès');
            return $this->redirectToRoute('app_admin_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book/index.html.twig', [
            'form_book' => ($formBook->createView()) ? $formBook : $formBook->createView(),
        ]);
    }
}
