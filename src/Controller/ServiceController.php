<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Form\ServiceUpdateType;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/creation', name: 'app_service_creation')]
    public function serviceMaker(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($service);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('service/service_maker.html.twig', [
            'serviceCreationForm' => $form->createView(),
        ]);
    }
    #[Route('/service/liste', name: 'app_service_liste')]
    public function partnerHome(ManagerRegistry $doctrine): Response
    {
        // Fetch all services
        $repository = $doctrine->getRepository(Service::class);
        $result = $repository->findAll();

        // Hydrate each partner with its structure and service
        // Could be optimized do to everything in one query to database instead of 3
//        foreach ($result as $partner){
//            $partner->getService();
//            $partner->getStructures();
//        }

        return $this->render('service/liste_service.html.twig', [
            'services' => $result,
//            'searchResults' => []
        ]);
    }

    #[Route('/service/update/{id}', name: 'app_service_update')]
    public function serviceUpdate(Service $service, Request $request, \Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ServiceUpdateType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_service_liste');
        }

        return $this->render('service/service_update.html.twig', [
            'serviceUpdateForm' => $form->createView(),
        ]);
    }

    #[Route('/service/delete/{id}', name: 'app_service_delete')]
    public function serviceDelete(Service $service, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($service);
        $entityManager->flush();
        return $this->redirectToRoute('app_service_liste');
    }

}
