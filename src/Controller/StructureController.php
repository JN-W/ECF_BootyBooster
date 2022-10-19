<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/structure/creation', name: 'app_structure_creation')]
    public function partnerMaker(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($structure);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('structure/structure_maker.html.twig', [
            'structureCreationForm' => $form->createView(),
        ]);
    }
}
