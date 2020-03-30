<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class RegistrationController extends AbstractController
{
    private $mailer;
    private $translator;

    public function __construct(TranslatorInterface $translator, \Swift_Mailer $mailer)
    {
        $this->translator = $translator;
        $this->mailer = $mailer;
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

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
        if (!$token) throw $this->createNotFoundException('aucun token');
        $token = (new Parser())->parse((string) $token);

        $user_id = $token->getClaim('user_id');
        if (!$user_id) throw $this->createAccessDeniedException('token invalide');

        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($user_id);
        if (!$user) throw $this->createAccessDeniedException('Utilisateur introuvable');

        if ($user->getHasConfirmEmail()) throw $this->createAccessDeniedException('Utilisateur déjà confirmé');
        $user->setHasConfirmEmail(true);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('notice', 'success.confirm.email');

        return $this->redirectToRoute('app_login');
    }

    private function send_mail(User $user, \Swift_Mailer $mailer)
    {
        $token = $this->generateToken($user->getId());

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

        $mailer->send($message);
    }

    private function generateToken($user_id)
    {
        $time = time();
        $token = (new Builder())
            ->issuedAt($time)
            ->expiresAt($time + (int) $_ENV['token_expiration'])
            ->withClaim('user_id', $user_id)
            ->getToken();

        return $token;
    }
}
