<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Admin/Editor')]
class EditorController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/new', name: 'app_admin_editor')]
    public function newEditor(Request $request): Response
    {
        $editor = new Editor();
        $formEditor = $this->createForm(EditorType::class, $editor);
        $formEditor->handleRequest($request);

        if ($formEditor->isSubmitted() && $formEditor->isValid()) {
            $this->entityManagerInterface->persist($editor);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'Validation : l\'éditeur '.$editor->getName().' a été ajouté avec succès.'); 
            return $this->redirectToRoute('app_admin_editor');
        }

        return $this->render('admin/editor/index.html.twig', [
            'form_editor' => ($formEditor->createView()) ? $formEditor : $formEditor->createView(),
        ]);
    }
}
