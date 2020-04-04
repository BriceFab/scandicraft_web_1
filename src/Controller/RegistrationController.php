<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\JWTService;
use App\Service\TokenAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    private $em;
    private $mailer;
    private $translator;
    private $JWTService;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, \Swift_Mailer $mailer, JWTService $JWTService)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->JWTService = $JWTService;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('accueil');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            //Send email
            $this->addFlash('notice', $this->translator->trans('notif.confirm.email', ['email' => $user->getEmail()]));
            $this->send_mail($user, $this->mailer);

            return $this->redirectToRoute('accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/inscription/confirmation/{token}", name="register_confirm")
     */
    public function confirmation(Request $request, $token): Response
    {
        $user = $this->JWTService->verifyToken($token, TokenAction::REGISTER_CONFIRMATION);

        if ($user->getHasConfirmEmail()) throw $this->createNotFoundException('Utilisateur déjà confirmé');
        $user->setHasConfirmEmail(true);

        $this->em->persist($user);
        $this->em->flush();

        $this->addFlash('notice', 'success.confirm.email');

        return $this->redirectToRoute('app_login');
    }

    private function send_mail(User $user)
    {
        $token = $this->JWTService->generateToken($user, TokenAction::REGISTER_CONFIRMATION, -1);

        $message = (new \Swift_Message('Confirmation compte - ScandiCraft'))
            ->setFrom($this->getParameter('mail.sender'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/confirm_email.html.twig',
                    [
                        'username' => $user->getUsername(),
                        'token' => $token,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

}
