<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $urlGenerator;
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, \Swift_Mailer $mailer)
    {
        $this->em = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->signer = new Sha256();
        $this->mailer = $mailer;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/reset_password", name="ask_reset_password")
     */
    public function askPassword(Request $request)
    {
        $email = $request->request->get('forgot_email');
        if (!$email) {
            throw $this->createNotFoundException(
                'email introuvable'
            );
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            //send email
            $this->send_mail($user);
        }

        $this->addFlash('notice', 'Si ce compte existe, vous aller recevoir un email pour réinitialiser votre mot de passe !');

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $token)
    {
        if (!$token) throw $this->createNotFoundException('aucun token');
        $token = (new Parser())->parse((string) $token);

        if (!$token->verify($this->signer, $_ENV['confirmation_key'])) throw $this->createAccessDeniedException('token invalide');

        $user_id = $token->getClaim('user_id');
        if (!$user_id) throw $this->createAccessDeniedException('token invalide');

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->find($user_id);
        if (!$user) throw $this->createAccessDeniedException('Utilisateur introuvable');

        //Display form
        $form = $this->createForm(ResetPasswordType::class, $user);
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

            $this->addFlash('notice', 'Mot de passe réinitialiser avec succès !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function send_mail(User $user)
    {
        $token = $this->generateToken($user->getId());

        $message = (new \Swift_Message('Réinitialisation du mot de passe - ScandiCraft'))
            ->setFrom($this->getParameter('mail.sender'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/reset_password.html.twig',
                    [
                        'username' => $user->getUsername(),
                        'token' => $token,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

    private function generateToken($user_id)
    {
        $time = time();
        $token = (new Builder())
            ->issuedAt($time)
            ->expiresAt($time + (int) $_ENV['token_expiration'])
            ->withClaim('user_id', $user_id)
            ->getToken($this->signer, new Key($_ENV['confirmation_key']));

        return $token;
    }
}
