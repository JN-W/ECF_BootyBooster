<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use App\Form\PartnerType;
use App\Form\StructureCreationFromUserType;
use App\Form\StructureCreationType;
use App\Form\StructureDataUpdateType;
use App\Form\StructureServiceUpdateType;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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

    #[Route('/structure/delete/{id}', name: 'app_structure_delete')]
    public function structureDelete(Structure $structure, ManagerRegistry $doctrine): Response
    {
        $idPartner = $structure->getPartner()->getId();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($structure);
        $entityManager->flush();
        return $this->redirectToRoute('app_partner_detail', [ 'id' => $idPartner]);
    }




    // STEP 1 of structure creation WITHOUT EXISTING USER : partner choice
    #[Route('/structure/creation/partner_selection', name: 'app_structure_creation_partner_selection')]
    public function structureMakerWithoutUserCreationStepPartner(ManagerRegistry $doctrine): Response
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

        // STEP 2 of structure creation WITHOUT EXISTING USER : structure building
        #[Route('/structure/creation/{id}/structure_data', name: 'app_structure_creation_structure_data')]
        #[ParamConverter('partner', class: 'SensioBlogBundle:Partner')]
        public function structureMakerWithoutUserCreationStepStructure(Request $request, Partner $partner, EntityManagerInterface $entityManager): Response
    {
        //Store partner id for redirection
        $idPartner = $partner->getId();

        // Get chosen partner service to apply to new structure
        $partner_service = $partner->getService();

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
            $entityManager->persist($structure);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_partner_detail', ['id' => $idPartner]);
        }

        return $this->render('structure/structure_maker.html.twig', [
            'structureCreationForm' => $form->createView(),
            'partner' => $partner
        ]);
    }

    // STEP 1 of structure creation WITH NEW USER : partner choice
    #[Route('/structure/creation/partner_selection/{id}', name: 'app_structure_creation_partner_selection_with_user')]
    public function structureMakerWithUserCreationStepPartner(User $user, ManagerRegistry $doctrine): Response
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

        return $this->render('structure/structure_maker_partner_selection_with_user.html.twig', [
            'partners' => $result,
            'user' => $user
        ]);
    }

    // STEP 2 of structure creation WITH NEW USER: structure building
    #[Route('/structure/creation/{id_partner}/structure_data/{id_user}', name: 'app_structure_creation_structure_data_with_user')]
    #[Entity('partner', options: ['id' => 'id_partner'])]
    #[Entity('user', options: ['id' => 'id_user'])]
    public function structureMakerWithUserCreationStepStructure(Request $request, Partner $partner,User $user , EntityManagerInterface $entityManager): Response
    {
        //Store partner id for redirection
        $idPartner = $partner->getId();

        // Get chosen partner service to apply to new structure
        $partner_service = $partner->getService();

        // Build new structure
        $structure = new Structure();
        // Define partner chosen
        $structure->setPartner($partner);
        $structure->setUser($user);
        // Add chosen partner services to structure
        foreach($partner_service as $service)
        {
            $structure->addService($service);
        }

        // Form for remaining needed input
        $form = $this->createForm(StructureCreationFromUserType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $currentRole = $user->getRoles();
            if(!in_array( "ROLE_STRUCTURE", $currentRole, $strict = false))
            {
                $currentRole[] = "ROLE_STRUCTURE";
            }
            $user->setRoles($currentRole);
            $entityManager->persist($structure);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_partner_detail', ['id' => $idPartner]);
        }

        return $this->render('structure/structure_maker_from_user.html.twig', [
            'structureCreationFromUserForm' => $form->createView(),
            'partner' => $partner,
            'user' => $user
        ]);
    }


    #[Route('/structure/detail/{id}', name: 'app_structure_detail')]
    public function displayStructure(Structure $structure, ManagerRegistry $doctrine): Response
    {
        // Get all services and partner from this structure
        $structure->getService();
        $structure->getPartner();
        $partner=$structure->getPartner();
        $partner->getId();

        return $this->render('structure/structure_detail.html.twig', [
            'structure' => $structure,
            'partner' => $partner
        ]);
    }

    #[Route('/structure/activation/{id}', name: 'app_structure_activation')]
    public function structure_activater(Structure $structure, ManagerRegistry $doctrine)
    {
        // Vérification à faire
        $structure->setIsActive(($structure->isIsActive()) ? false : true);
        $entityManager=$doctrine->getManager();
        $entityManager->persist($structure);
        $entityManager->flush();

        return new Response('true');
    }

    #[Route('/structure/service/update/{id}', name: 'app_structure_service_update')]
    public function structureServiceUpdate(Structure $structure,Request $request, ManagerRegistry $doctrine): Response
    {
        $partner = $structure->getPartner();
        $partner->getService();
        $service_from_partner = $partner->getService()->getValues();
        $idStructure = $structure->getId();
        $form = $this->createForm(StructureServiceUpdateType::class, $structure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_detail', ['id'=>$idStructure]);
        }

        return $this->render('structure/structure_service_update.html.twig', [
            'structureServiceUpdateForm' => $form->createView(),
            'structure' => $structure,
            'partner' => $partner,
            'service_from_partner' => $service_from_partner
        ]);
    }

    #[Route('/structure/data/update/{id}', name: 'app_structure_data_update')]
    public function partnerDataUpdate(Structure $structure,Request $request, ManagerRegistry $doctrine): Response
    {
        $partner = $structure->getPartner();
        $idStructure = $structure->getId();
        $form = $this->createForm(StructureDataUpdateType::class, $structure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_detail', ['id'=>$idStructure]);
        }



        return $this->render('structure/structure_data_update.html.twig', [
            'structureDataUpdateForm' => $form->createView(),
            'structure' => $structure,
            'partner' => $partner
        ]);
    }

}
