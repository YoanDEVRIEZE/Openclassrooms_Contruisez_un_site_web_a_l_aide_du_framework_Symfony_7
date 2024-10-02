<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Admin/Book')]
class BookController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;

    Public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/new', name: 'app_admin_book')]
    public function newAuthor(Request $request): Response
    {
        $book = new Book();
        $formBook = $this->createForm(BookType::class, $book);
        $formBook->handleRequest($request);

        if ($formBook->isSubmitted() && $formBook->isValid()) {
            $this->entityManagerInterface->persist($book);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'Validation : le livre '.$book->getTitle().' a été ajouté avec succès');
            return $this->redirectToRoute("app_admin_book");
        }

        return $this->render('admin/book/index.html.twig', [
            'form_book' => $formBook->createView(),
        ]);
    }
}
