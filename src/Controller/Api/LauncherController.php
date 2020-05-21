<?php

namespace App\Controller\Api;

use HttpStatusCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\Exception\JsonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Security;

/*
https://symfony.com/blog/new-in-symfony-3-2-file-controller-helper
*/

class LauncherController extends AbstractController
{
    private const unauthorized_message = "ScandiCraft est en maintenance. Et vous ne faites pas parti des joueurs autorisés à lancer le launcher !";
    private const launcher_role = "ROLE_LAUNCHER";
    private const launcher_files = "\\launcher\\files\\";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/launcher/download", name="launcher_download_files")
     * @Method("POST")
     */
    public function downloadFile(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $launcher_files = $this->getParameter('kernel.project_dir') . LauncherController::launcher_files;

        if (isset($data['files'])) {
            dd("data files");

            //TODO Json files array, if null = download all
            //TODO Pack in zip files

            // $this->checkIsAuthorized();

            // $filePath = $this->getParameter('kernel.project_dir') . LauncherController::launcher_files . $file;

            // $filesystem = new Filesystem();

            // if (!$filesystem->exists($filePath)) {
            //     throw new JsonException("Le fichier n'existe pas " . $filePath, HttpStatusCode::NO_CONTENT);
            // }

            // return $this->file($filePath, $file, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            // download all files
            // dd("download all files");
            $zip = new \ZipArchive();
            $zipName = 'all.zip';
            $zip->open($zipName,  \ZipArchive::CREATE);

            $finder = new Finder();
            $finder->files()->in($launcher_files);

            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $fileNameWithExtension = $file->getRelativePathname();

                    $zip->addFromString($fileNameWithExtension,  file_get_contents($absoluteFilePath));
                }
            }
            $zip->close();

            return $this->file($zipName, $zipName, ResponseHeaderBag::DISPOSITION_INLINE);
        }
    }

    /**
     * @Route("/launcher/installer", name="launcher_installer")
     */
    public function downloadInstaller()
    {
        return $this->json("test");
    }

    private function checkIsAuthorized()
    {
        if (!$this->security->isGranted(LauncherController::launcher_role)) {
            throw new JsonException(LauncherController::unauthorized_message, HttpStatusCode::UNAUTHORIZED);
        }
    }
}
