<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
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
            $entityManager->persist($partner);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('partner/partner_maker.html.twig', [
            'partnerCreationForm' => $form->createView(),
        ]);
    }
}
