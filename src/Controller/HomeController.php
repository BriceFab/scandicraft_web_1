<?php

namespace App\Controller;

use App\Repository\SpoilRepository;
use App\Service\DiscordService;
use App\Service\RconService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     * @param SpoilRepository $spoilRepository
     * @param DiscordService $discordService
     * @return Response
     */
    public function index(SpoilRepository $spoilRepository, DiscordService $discordService)
    {
        $last = $spoilRepository->findOneBy([], ['createdAt' => 'DESC']);
        $missing_share = 0;
        if ($last && $last->getGoalType()) {
            switch ($last->getGoalType()->getName()) {
                case 'social_share':
                    $missing_share = $last->getShareGoal() - $last->getCurrentSocialShare();
                    break;
                case 'discord_members':
                    $missing_share = $last->getShareGoal() - $discordService->countMembers();
                    break;
            }
        }

        return $this->render('home/index.html.twig', [
            'last_spoil_missing_number' => $missing_share > 0 ? $missing_share : 0,
            'last_spoil' => $last
        ]);
    }

    /**
     * @Route("/jouer", name="jouer")
     * @Route("/play")
     * @Route("/join")
     */
    public function jouer(RconService $rconService)
    {
        $success = $rconService->executeFactionCommand("say hello from rcon");
        dd($success);

        return $this->render('jouer/index.html.twig');
    }

    // /**
    //  * @Route("/test")
    //  */
    // public function test()
    // {
    //     $client = HttpClient::create([
    //         'headers' => [
    //             'Authorization' => 'Bot NzA0NzcyNzg3NjE2ODc0NjE3.XuaJDA.FPcViF2bS0X4QfSBNwK50sHVWFI'
    //         ]
    //     ]);
    //     $response = $client->request('GET', 'https://discordapp.com/api/guilds/683627920366764046/members?limit=1000');
    //     $content = $response->getContent();
    //     $contentAsArray = $response->toArray();

    //     // // return $this->json(count($contentAsArray['members']));
    //     return $this->json(count($contentAsArray));
    // }
}
