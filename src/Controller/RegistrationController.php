<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Twig\NumberFormatConfig;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register( MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from(new Address('alienmailer@exemple.com','Booty coach'))
                ->to(new Address($user->getEmail(), 'New player'))
                -> subject('En route vers le succès')
                ->htmlTemplate('mail/welcome.html.twig')
                ->context([
                    'user' => $user
                    ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/plus_partner', name: 'app_register_plus_partner')]
    public function registerAndCreatePartner( MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $idUser = $user->getId();
            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from(new Address('alienmailer@exemple.com','Booty coach'))
                ->to(new Address($user->getEmail(), 'New player'))
                -> subject('En route vers le succès')
                ->htmlTemplate('mail/welcome.html.twig')
                ->context([
                    'user' => $user
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_partner_creation_from_user', ['id' => $idUser]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/plus_structure', name: 'app_register_plus_structure')]
    public function registerAndCreateStructure( MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $idUser = $user->getId();
            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from(new Address('alienmailer@exemple.com','Booty coach'))
                ->to(new Address($user->getEmail(), 'New player'))
                -> subject('En route vers le succès')
                ->htmlTemplate('mail/welcome.html.twig')
                ->context([
                    'user' => $user
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_structure_creation_partner_selection_with_user', ['id' => $idUser]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
