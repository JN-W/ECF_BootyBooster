<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PartnerController extends AbstractController
{
    #[Route('/partner/creation', name: 'app_partner_creation')]
    public function partnerMaker(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $currentRole = $partner->getUser()->getRoles();
            if(!in_array( "ROLE_PARTNER", $currentRole, $strict = false))
            {
                $currentRole[] = "ROLE_PARTNER";
            }
            $partner->getUser()->setRoles($currentRole);
            $entityManager->persist($partner);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('partner/partner_maker.html.twig', [
            'partnerCreationForm' => $form->createView(),
        ]);
    }

    #[Route('/partner/home', name: 'app_partner_home')]
    public function partnerHome(ManagerRegistry $doctrine): Response
    {
        // Fetch all partners
        $repository = $doctrine->getRepository(Partner::class);
        $result = $repository->findAllOrderedByName();

        // Hydrate each partner with its structure and service
        // Could be optimized do to everything in one query to database instead of 3
        foreach ($result as $partner){
            $partner->getService();
            $partner->getStructures();
        }

        return $this->render('partner/partner_home.html.twig', [
            'partners' => $result,
//            'searchResults' => []
        ]);
    }




    #[Route('/partner/update/{id}', name: 'app_partner_update')]
    public function partnerTest(Partner $partner,Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_partner_home');
        }

        return $this->render('partner/partner_maker.html.twig', [
            'partnerCreationForm' => $form->createView(),
        ]);
    }

    #[Route('/partner/delete/{id}', name: 'app_partner_delete')]
    public function deleteNews(Partner $partner, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($partner);
        $entityManager->flush();
        return $this->redirectToRoute('app_partner_home');
    }

    #[Route('/partner/activation/{id}', name: 'app_partner_activation')]
    public function partner_activater(Partner $partner, ManagerRegistry $doctrine)
    {
        // Vérification à faire
        $partner->setIsActive(($partner->isIsActive()) ? false : true);

        $entityManager=$doctrine->getManager();
        $entityManager->persist($partner);
        $entityManager->flush();

        return new Response('true');
    }

    #[Route('/partner/recherche', name: 'app_partner_searcher', methods: ['POST']  )]
    public function partner_searcher( ManagerRegistry $doctrine, Request $request)
    {
        $userSearchText = $request->request->get('userSearchText');

        $repository = $doctrine->getRepository(Partner::class);
        $searchResults = $repository->findByWord($userSearchText);

        // J'initialise un tableau vide
        $formated_partner=[];
        $JSON_formated_partner=[];

        // Boucle sur les résultats de la recherche pour parcourir les partners trouvés un par un ($Result)
        foreach ($searchResults as $Result ){
            // Je transforme le partner en un array de 2 éléments
            $Format_Result = $Result->GetFormat();
            $JSON_Format_Result = json_encode($Result->GetFormat());
            // Je la push à la fin de mon tableau de collecte des résultats
            array_push($formated_partner,$Format_Result);
            array_push($JSON_formated_partner,$JSON_Format_Result);
            // TO DO : affiché l'état actif/inactif
        }


        // Envoi de la réponse en JSON
        return new JsonResponse($JSON_formated_partner);
    }

    #[Route('/partner/detail/{id}', name: 'app_partner_detail')]
    public function displayPartner(Partner $partner, ManagerRegistry $doctrine): Response
    {
        // Get all structures from this partner and their services
        $partner_service = $partner->getService();
        $partner_structure = $partner->getStructures();
        $partnerId = $partner->getId();
        dump($partner);
        dump($partner_service);
        dump($partner_structure);
        $result = [];
        foreach($partner_structure as $structure){
            $result[$structure->getAddress()] = $structure->getService()->getValues();
//            dump($result);

        }

//        // Count how many structure use each service
//        $structureRepository = $doctrine->getRepository(Structure::class);
//        $count = $structureRepository->countStructureWithThisService($partnerId);
//        dump($count);

        return $this->render('partner/partner_test.html.twig', [
            'partner' => $partner,
            'result' => $result,
            'globalService' => $partner_service,
//            'count' => $count
        ]);
    }
}
