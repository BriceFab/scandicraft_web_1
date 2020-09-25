<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends EasyAdminController
{

    /**
     * @Route("%ADMIN_PATH%/stats", name="admin_stats")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function statsPage(Request $request, UserRepository $userRepository)
    {
        $monthy_new_register_users_goal = 10;   //en pourcentage

        return $this->render("easy_admin/page/stats.twig", [
            'total_users' => $userRepository->count([]),
            'total_users_confirmed' => $userRepository->count(['hasConfirmEmail' => true]),
            'monthly_users' => $userRepository->countUsersRegisterThisMonth(),
            'monthy_new_register_users_goal' => $monthy_new_register_users_goal,
        ]);
    }

}
