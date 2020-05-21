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
    private const zip_name = "scandicraft_download.zip";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/api/launcher/download", name="launcher_download_files")
     * @Method("POST")
     */
    public function downloadFile(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $launcher_files = $this->getParameter('kernel.project_dir') . LauncherController::launcher_files;

        $this->createZipFile($launcher_files, isset($data['files']) ? $data['files'] : []);

        return $this->file(LauncherController::zip_name, LauncherController::zip_name, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function createZipFile($launcher_files, $need_download_files)
    {
        $zip = new \ZipArchive();
        $zip->open(LauncherController::zip_name,  \ZipArchive::CREATE);

        $finder = new Finder();
        $finder->files()->in($launcher_files);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();

                if (!isset($need_download_files) || in_array($fileNameWithExtension, $need_download_files)) {
                    $zip->addFromString($fileNameWithExtension,  file_get_contents($absoluteFilePath));
                }
            }
        }
        $zip->close();
    }

    /**
     * @Route("/api/launcher/checksum", name="launcher_files_checksum")
     * @Method("GET")
     */
    public function getChecksum()
    {
        $files_checksum = [];

        $launcher_files = $this->getParameter('kernel.project_dir') . LauncherController::launcher_files;

        $finder = new Finder();
        $finder->files()->in($launcher_files);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();

                array_push($files_checksum, [
                    "name" => $fileNameWithExtension,
                    "size" => filesize($absoluteFilePath),
                    "hash" => hash_file('sha1', $absoluteFilePath)
                ]);
            }
        }

        return $this->json($files_checksum, HttpStatusCode::OK);
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
