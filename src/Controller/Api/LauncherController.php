<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\Exception\JsonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Yaml\Yaml;

/*
https://symfony.com/blog/new-in-symfony-3-2-file-controller-helper
*/
class LauncherController extends AbstractController
{
    private const unauthorized_message = "ScandiCraft est en maintenance. Et vous ne faites pas parti des joueurs autorisés à lancer le launcher !";
    private const launcher_role = "ROLE_LAUNCHER";
    private const launcher_files = "/launcher/files/";
    private const launcher_installers = "/launcher/installer/";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/api/launcher/download", name="launcher_download_files")
     * @Method("POST")
     * @param Request $request
     * @return BinaryFileResponse
     * @throws JsonException
     */
    public function downloadFile(Request $request)
    {
        $this->checkIsAuthorized();

        $zip_name = "scandicraft_download" . uniqid() . ".zip";

        $data = json_decode($request->getContent(), true);
        $launcher_files = $this->getParameter('kernel.project_dir') . LauncherController::launcher_files;

        $this->createZipFile($zip_name, $launcher_files, isset($data['files']) ? $data['files'] : []);

        $response = new BinaryFileResponse($zip_name);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $zip_name);
        $response->deleteFileAfterSend();

        return $response;
    }

    private function createZipFile($zip_name, $launcher_files, $need_download_files)
    {
        $zip = new \ZipArchive();
        $zip->open($zip_name,  \ZipArchive::CREATE);

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
     * @throws JsonException
     */
    public function getChecksum()
    {
        $this->checkIsAuthorized();

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

        return $this->json($files_checksum, Response::HTTP_OK);
    }

    /**
     * @Route("/launcher/installer/{os}", name="launcher_installer")
     */
    public function downloadInstaller($os)
    {
        $installers = $this->getParameter('kernel.project_dir') . LauncherController::launcher_installers;

        $latestFile = Yaml::parseFile($installers . $this->getLatestBuild($os));

        return $this->file($installers . $latestFile['path']);
    }

    private function getLatestBuild($os)
    {
        switch ($os) {
            case 'win':
                return 'latest.yml';
            case 'mac':
                return 'latest-mac.yml';
            case 'linux':
                return 'latest-linux.yml';
            default:
                //default = windows
                return 'latest.yml';
        }
    }

    /**
     * @Route("/launcher/update/{file}", name="launcher_update")
     */
    public function updateLauncher($file)
    {
        $installers = $this->getParameter('kernel.project_dir') . LauncherController::launcher_installers;

        return $this->file($installers . $file);
    }

    private function checkIsAuthorized()
    {
        if (!$this->security->isGranted(LauncherController::launcher_role)) {
            throw new JsonException(LauncherController::unauthorized_message, Response::HTTP_UNAUTHORIZED);
        }
    }
}
