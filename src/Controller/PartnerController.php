<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
