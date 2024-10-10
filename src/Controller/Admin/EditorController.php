<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/Admin/Editor')]
class EditorController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private EditorRepository $editorRepository;

    public function __construct(EntityManagerInterface $entityManagerInterface, EditorRepository $editorRepository) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->editorRepository = $editorRepository;
    }

    #[Route('', name:'app_admin_editor')]
    public function editor(Request $request) : Response 
    {
        $qb = $this->editorRepository->findListeEditor();;

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($qb),
        );

        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->getCurrentPageResults();

        return $this->render('admin/editor/index.html.twig', [
            'liste_editor' => $pagerfanta,
        ]);
    }

    #[Route('/Show/{id}', name:'app_admin_editor_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Editor $editor) : Response 
    {
        return $this->render('admin/editor/show.html.twig', [
            'editor' => $editor,
        ]);
    }

    #[Route('/new', name: 'app_admin_editor_new')]
    #[Route('/{id}/Edit', name: 'app_admin_editor_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function newEditor(?Editor $editor, Request $request): Response
    {
        $editor ??= new Editor();
        $formEditor = $this->createForm(EditorType::class, $editor);
        $formEditor->handleRequest($request);

        if ($formEditor->isSubmitted() && $formEditor->isValid()) {
            $this->entityManagerInterface->persist($editor);
            $this->entityManagerInterface->flush();

            if ($request->attributes->get('_route') === 'app_admin_editor_new') {
                $this->addFlash('success', 'Validation : l\'éditeur '.$editor->getName().' a été ajouté avec succès.'); 
                return $this->redirectToRoute('app_admin_editor_new');
            }

            $this->addFlash('success', 'Validation : l\'éditeur '.$editor->getName().' a été modifié avec succès.'); 
            return $this->redirectToRoute('app_admin_editor_show', ['id' => $editor->getId()]);
        }

        return $this->render('admin/editor/index.html.twig', [
            'form_editor' => ($formEditor->createView()) ? $formEditor : $formEditor->createView(),
        ]);
    }
}
