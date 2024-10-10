<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Catalogue')]
class BookController extends AbstractController
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    #[Route('', name: 'app_book')]
    public function book(Request $request): Response
    {
        $qb = $this->bookRepository->findListeBook();

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($qb),
        );
        
        $pagerfanta->setMaxPerPage(3);

        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setMaxPerPage(8);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->getCurrentPageResults();
        
        return $this->render('book/index.html.twig', [
            'liste_book' => $pagerfanta,
        ]);
    }
}
