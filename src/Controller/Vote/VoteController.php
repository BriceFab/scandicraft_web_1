<?php

namespace App\Controller\Vote;

use App\Entity\UserVote;
use App\Entity\VoteSite;
use App\Repository\UserVoteRepository;
use App\Repository\VoteSiteRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    private $em;
    private $parameter;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameter)
    {
        $this->em = $em;
        $this->parameter = $parameter;
    }

    /**
     * @Route("/voter", name="voter", options={"sitemap"="true"})
     * @param UserVoteRepository $userVoteRepository
     * @param VoteSiteRepository $voteSiteRepository
     * @return Response
     */
    public function voterPage(UserVoteRepository $userVoteRepository, VoteSiteRepository $voteSiteRepository)
    {
        $top_votes = $userVoteRepository->getTopVotes();

        return $this->render('vote/index.html.twig', [
            "vote_sites" => $voteSiteRepository->findBy(['active' => true]),
            "top_votes" => $top_votes,
        ]);
    }

    /**
     * @Route("/vote/verifier/{vote_site_id}", name="verify_vote")
     * @ParamConverter("voteSite", options={"mapping": {"vote_site_id": "id"}})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param VoteSite $voteSite
     * @return RedirectResponse
     */
    public function valideVote(Request $request, VoteSite $voteSite)
    {
        $user_ip = $this->parameter->get('APP_ENV') == 'dev' ? $this->parameter->get('VOTE_DEV_TEST_IP') : $request->getClientIp();

        //Vérification du vote
        $has_vote = $this->verifyServerPriveVote($user_ip);

        //Ajout du vote + récompenses
        if ($has_vote) {
            $this->addFlash('notice', 'Merci pour votre vote !');

            //Enregistrement du vote dans la base de donnée
            $user_vote = new UserVote();
            $user_vote->setVoteSite($voteSite);
            $user_vote->setUser($this->getUser());
            $user_vote->setCreatedAt(new DateTime('now'));
            $user_vote->setVoteId($has_vote);
            $user_vote->setUserIp($user_ip);

            $this->em->persist($user_vote);
            $this->em->flush();
        }

        return $this->redirectToRoute('voter');
    }

    private function verifyServerPriveVote($client_ip)
    {
        $API_key = $this->parameter->get('VOTE_SERVER_PRIVE_KEY'); // Token du serveur

        $client = HttpClient::create([
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        $json = $client->request('GET', "https://serveur-prive.net/api/vote/json/$API_key/$client_ip");
        $json_data = json_decode($json->getContent(), true);

        if ($json_data['status'] === "1") {
            //Vérification dans la bdd, que le vote n'a pas déjà été comptabilisé
            /** @var UserVote $user_vote */
            $user_vote = $this->em->getRepository(UserVote::class)->findOneBy(["vote_id" => $json_data['vote']]);

            if ($user_vote != null) {
                /** @var DateTime $vote_at_date */
                $vote_at_date = clone $user_vote->getCreatedAt();
                $add_seconds = $user_vote->getVoteSite()->getTimeWaitVote();
                try {
                    $next_vote_date = $vote_at_date->add(new DateInterval('PT' . $add_seconds . 'S'));
                } catch (Exception $e) {
                    $this->addFlash('error', "Une erreur est survenue (vote date)");
                    return false;
                }
                $current_date = new DateTime('now');
                $site_name = $user_vote->getVoteSite()->getName();

                if ($next_vote_date > $current_date) {
                    $next_vote = intval($json_data["nextvote"] / 60);
                    $this->addFlash('error', "Vous devez attendre $next_vote minutes avant le prochain vote pour le site $site_name.");
                    return false;
                } else {
                    return $json_data['vote'];
                }
            } else {
                return $json_data['vote'];
            }
        } else {
            $this->addFlash('error', "Vous n'avez pas voté..");
            return false;
        }
    }
}
