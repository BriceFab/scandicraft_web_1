<?php

namespace App\Controller\User;

use App\Entity\UserSocialmedia;
use App\Form\UserSocialmediaType;
use App\Repository\UserVoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    const SKIN_EXTENSION_FORMATS = ["image/png"];

    /**
     * @Route("/compte", name="compte")
     * @param UserVoteRepository $userVoteRepository
     * @return RedirectResponse|Response
     */
    public function comptePage(UserVoteRepository $userVoteRepository)
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
     * @Route("/compte/upload/skin", name="upload_skin", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadSkin(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('skin');

        //check has upload file
        if (!$file) {
            return $this->json(["message" => "fichier introuvable"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check extension
        if (!in_array($file->getMimeType(), self::SKIN_EXTENSION_FORMATS)) {
            return $this->json(["message" => "fichier extension format incorrect"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check dimensions
        $width = getimagesize($file)[0];
        $height = getimagesize($file)[1];
        if ($width !== 64 && ($height !== 64 || $height !== 32)) {
            return $this->json(["message" => "fichier dimensions incorrect"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check size
        if ($file->getSize() >= 1000000) { //1000000 bytes = 1 MB
            return $this->json(["message" => "fichier trop grand doit être moins de 1 MB"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //save in upload dir
        $file->move(
            $this->getParameter('skins_directory'),
            $this->getUser()->getUsername() . "." . $file->guessExtension()
        );

        return $this->json(["response" => "upload ok"], Response::HTTP_OK);
    }

}