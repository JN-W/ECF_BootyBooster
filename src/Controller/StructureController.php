<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Form\StructureCreationType;
use App\Form\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/structure/creation', name: 'app_structure_creation')]
    public function structureMaker(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $currentRole = $structure->getUser()->getRoles();
            if(!in_array( "ROLE_STRUCTURE", $currentRole, $strict = false))
            {
                $currentRole[] = "ROLE_STRUCTURE";
            }
            $structure->getUser()->setRoles($currentRole);
            $entityManager->persist($structure);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('structure/structure_maker.html.twig', [
            'structureCreationForm' => $form->createView(),
        ]);
    }

    #[Route('/structure/creation/partner_selection', name: 'app_structure_creation_partner_selection')]
    public function structureMakerTestStepPartner(ManagerRegistry $doctrine): Response
    {
        // Fetch all partners
        $repository = $doctrine->getRepository(Partner::class);
        $result = $repository->findAllOrderedByName();

        // Hydrate each partner with its structure and service
        // Could be optimized do to everything in one query to database instead of 3
        foreach ($result as $partner) {
            $partner->getService();
            $partner->getStructures();
        }

        return $this->render('structure/structure_maker_partner_selection.html.twig', [
            'partners' => $result,
//            'searchResults' => []
        ]);
    }

        // STEP 2 of structure creation
        #[Route('/structure/creation/{id}/structure_data', name: 'app_structure_creation_structure_data')]
        #[ParamConverter('partner', class: 'SensioBlogBundle:Partner')]
        public function structureMakerStepStructure(Request $request, Partner $partner, EntityManagerInterface $entityManager): Response
    {
        // Get chosen partner service to apply to new structure
        $partner_service = $partner->getService();

        // Prepare structure entity manager
//        $entityManager = $doctrine->getRepository(Structure::class);

        // Build new structure
        $structure = new Structure();

        // Define partner chosen
        $structure->setPartner($partner);

        // Add chosen partner services to structure
        foreach($partner_service as $service)
        {
            $structure->addService($service);
        }

        // Form for remaining needed input
        $form = $this->createForm(StructureCreationType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $currentRole = $structure->getUser()->getRoles();
            if(!in_array( "ROLE_STRUCTURE", $currentRole, $strict = false))
            {
                $currentRole[] = "ROLE_STRUCTURE";
            }

            $structure->getUser()->setRoles($currentRole);
//            dd($structure);

            $entityManager->persist($structure);

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('structure/structure_maker.html.twig', [
            'structureCreationForm' => $form->createView(),
            'partner' => $partner
        ]);
    }


}
