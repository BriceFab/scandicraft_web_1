<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\UserSocialmedia;
use App\Form\ResetPasswordType;
use App\Form\UserSocialmediaType;
use App\Repository\UserVoteRepository;
use App\Service\JWTService;
use App\Service\TokenAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $em;
    private $mailer;
    private $JWTService;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, JWTService $JWTService)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->JWTService = $JWTService;
    }

    /**
     * @Route("/compte", name="compte")
     * @param UserVoteRepository $userVoteRepository
     * @return RedirectResponse|Response
     */
    public function index(UserVoteRepository $userVoteRepository)
    {
        if (!$this->getUser() || $this->getUser() == null) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page !');
            return $this->redirectToRoute('app_login');
        }

        //Forms
        $network = new UserSocialmedia();
        $form_network = $this->createForm(UserSocialmediaType::class, $network);

        return $this->render('user/compte/index.twig', [
            'user' => $this->getUser(),
            'form_network' => $form_network->createView(),
            'nombre_votes' => count($userVoteRepository->getUserMonthlyVotes($this->getUser())),
        ]);
    }

    /**
     * @Route("/compte/add_network", name="compte_add_network", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addNetwork(Request $request)
    {
        $network = new UserSocialmedia();

        $form_network = $this->createForm(UserSocialmediaType::class, $network);

        $form_network->handleRequest($request);

        if ($form_network->isSubmitted() && $form_network->isValid()) {
            // ... do your form processing, like saving the Task and Tag entities
        }

        return $this->index();
    }

    /**
     * @Route("/reset_password", name="ask_reset_password")
     * @param Request $request
     * @return RedirectResponse
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
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param $token
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $token)
    {
        $user = $this->JWTService->verifyToken($token, TokenAction::RESET_PASSWORD);

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
        $token = $this->JWTService->generateToken($user, TokenAction::RESET_PASSWORD);

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
}
