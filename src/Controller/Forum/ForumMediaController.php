<?php

namespace App\Controller\Forum;

use JsonException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ForumMediaController extends ForumController
{
    /**
     * @Route("/forum_data/post/image", name="forum_post_image", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postImage(Request $request, Security $security)
    {
        try {
            $files_result = $this->uploadFiles($request->files, $security->getUser()->getId(), $request);

            return $this->json([
                'result' => $files_result
            ], Response::HTTP_OK);
        } catch (FileException $e) {
            return $this->json([
                'errorMessage' => 'Erreur lors de l\'upload du fichier'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function uploadFiles($files, $user_id, $request)
    {
        $files_result = [];

        foreach ($files as $key => $file) {
            /** @var UploadedFile $file */
            if ($file->getSize() > 3000000) { //3000000 bytes = 3 MB
                throw new JsonException('Fichier trop grand (plus de 3 MB)');
            }

            // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $user_id . '-' . uniqid() . '.' . $file->guessExtension();

            $file_result = $file->move(
                $this->getParameter('forum_images_directory'),
                $newFilename
            );

            $files_result[] = [
                'name' => $newFilename,
                'size' => $file_result->getSize(),
                'url' => $request->getSchemeAndHttpHost() . $this->getParameter('forum_images_url') . $newFilename,
            ];
        }

        return $files_result;
    }
}
