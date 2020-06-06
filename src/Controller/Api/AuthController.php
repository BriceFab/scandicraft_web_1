<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Security;

class AuthController extends AbstractController
{
    private $em;
    private $JWTManager;
    private $security;

    public function __construct(EntityManagerInterface $em, JWTTokenManagerInterface $JWTManager, Security $security)
    {
        $this->em = $em;
        $this->JWTManager = $JWTManager;
        $this->security = $security;
    }

    /**
     * @Route("/api/verify_token", name="server_verify_token", methods={"POST"})
     */
    public function verifyToken(Request $request, UserRepository $user_repo)
    {
        $data = json_decode($request->getContent(), true);

        //Get json data
        $token = $data["token"];
        if (!$token) return $this->json(["error" => "invalid token"], Response::HTTP_UNAUTHORIZED);
        $uuid = $data["uuid"];
        if (!$uuid) return $this->json(["error" => "invalid uuid"], Response::HTTP_UNAUTHORIZED);
        $username = $data["username"];
        if (!$username) return $this->json(["error" => "invalid username"], Response::HTTP_UNAUTHORIZED);

        /* Verify */
        $payload = $this->JWTManager->decode($this->security->getToken());

        //Verify user
        $token_username = $payload['username'];
        if ($username != $token_username) return $this->json(["error" => "invalid username"], Response::HTTP_UNAUTHORIZED);

        //Verify uuid
        $user = $user_repo->findOneBy(["username" => $token_username]);
        if (!$user) return $this->json(["error" => "invalid user"], Response::HTTP_UNAUTHORIZED);

        $user_uuid = $user->getUuid();
        if ($user_uuid == null) {
            //set user uuid
            $user->setUuid($uuid);

            $this->em->persist($user);
            $this->em->flush();
        } else {
            //verify user uuid
            if ($uuid != $user_uuid) return $this->json(["error" => "invalid user uuid"], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            "username" => $user->getUsername(),
            "uuid" => $user->getUuid()
        ], Response::HTTP_OK);
    }
}
