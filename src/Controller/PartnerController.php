<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Service;
use App\Entity\Structure;
use App\Entity\User;
use App\Form\PartnerDataUpdateType;
use App\Form\PartnerFromUserType;
use App\Form\PartnerServiceUpdateType;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


class PartnerController extends AbstractController
{
    #[Route('/partner/creation', name: 'app_partner_creation')]
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function partnerMaker(MailerInterface $mailer, Request $request,  EntityManagerInterface $entityManager): Response
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
            $email = (new TemplatedEmail())
                ->from(new Address('YourBootyCoach@exemple.com','Booty coach'))
                ->to(new Address($partner->getUser()->getEmail(), 'New player'))
                -> subject('Bienvenu à bord partenaire !')
                ->htmlTemplate('mail/new_partner.html.twig')
                ->context([
                    'partner' => $partner
                ]);

            $mailer->send($email);
            return $this->redirectToRoute('app_partner_home');
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
    #[security("is_granted('ROLE_FRANCHISE')")]
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
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function deletePartner(MailerInterface $mailer, Partner $partner, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($partner);
        $entityManager->flush();
        $email = (new TemplatedEmail())
            ->from(new Address('YourBootyCoach@exemple.com','Booty coach'))
            ->to(new Address($partner->getUser()->getEmail(), 'New player'))
            -> subject('Au revoir partenaire...')
            ->htmlTemplate('mail/delete.html.twig')
            ->context([
                'partner' => $partner
            ]);

        $mailer->send($email);
        return $this->redirectToRoute('app_partner_home');
    }

    #[Route('/partner/activation/{id}', name: 'app_partner_activation')]
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function partner_activater(Partner $partner, ManagerRegistry $doctrine)
    {
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
        $newstr = filter_var($userSearchText,FILTER_SANITIZE_STRING);

        $repository = $doctrine->getRepository(Partner::class);
        $searchResults = $repository->findByWord($newstr);

        dump($newstr);

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
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function displayPartner(Partner $partner, ManagerRegistry $doctrine): Response
    {
        $serviceCounter = $partner->getService()->count();
        $structureCounter = $partner->getStructures()->count();
        // Get all structures from this partner and their services
        $partner->getId();
        $partner->getService();
        $partner->getStructures();

        return $this->render('partner/partner_detail.html.twig', [
            'partner' => $partner,
            'serviceCounter' => $serviceCounter,
            'structureCounter' => $structureCounter
        ]);
    }

    #[Route('/partner/service/update/{id}', name: 'app_partner_service_update')]
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function partnerServiceUpdate(Partner $partner,Request $request, ManagerRegistry $doctrine): Response
    {
        $idPartner = $partner->getId();
        $form = $this->createForm(PartnerServiceUpdateType::class, $partner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_partner_detail', ['id'=>$idPartner]);
        }

        return $this->render('partner/partner_service_update.html.twig', [
            'partnerServiceUpdateForm' => $form->createView(),
            'partner' => $partner
        ]);
    }

    #[Route('/partner/data/update/{id}', name: 'app_partner_data_update')]
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function partnerDataUpdate(Partner $partner,Request $request, ManagerRegistry $doctrine): Response
    {
        $idPartner = $partner->getId();
        $form = $this->createForm(PartnerDataUpdateType::class, $partner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_partner_detail', ['id'=>$idPartner]);
        }



        return $this->render('partner/partner_data_update.html.twig', [
            'partnerDataUpdateForm' => $form->createView(),
            'partner' => $partner
        ]);
    }

    #[Route('/partner/creation/{id}', name: 'app_partner_creation_from_user')]
    #[security("is_granted('ROLE_FRANCHISE')")]
    public function partnerMakerFromUser(MailerInterface $mailer,User $user,Request $request,  EntityManagerInterface $entityManager): Response
    {
        $partner = new Partner();
        $partner->setUser($user);
        $form = $this->createForm(PartnerFromUserType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $currentRole = $user->getRoles();
            if(!in_array( "ROLE_PARTNER", $currentRole, $strict = false))
            {
                $currentRole[] = "ROLE_PARTNER";
            }
            $user->setRoles($currentRole);
            $entityManager->persist($partner);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from(new Address('YourBootyCoach@exemple.com','Booty coach'))
                ->to(new Address($partner->getUser()->getEmail(), 'New player'))
                -> subject('Bienvenu à bord partenaire !')
                ->htmlTemplate('mail/new_partner.html.twig')
                ->context([
                    'partner' => $partner
                ]);

            $mailer->send($email);

            $idPartner = $partner->getId();
            return $this->redirectToRoute('app_partner_detail', ['id'=>$idPartner]);
        }

        return $this->render('partner/partner_maker_from_user.html.twig', [
            'partnerCreationFromUserForm' => $form->createView(),
            'user' => $user
        ]);
    }


}
