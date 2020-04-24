<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json([
            "user" => $this->getUser() ? $this->getUser()->getId() : null,
            "roles" => $this->getUser() ? $this->getUser()->getRoles() : null
        ]);
    }
}
