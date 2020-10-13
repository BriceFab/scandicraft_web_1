<?php

namespace App\Controller\Spoil;

use App\Entity\SocialmediaType;
use App\Entity\Spoil;
use App\Entity\SpoilShare;
use App\Repository\SpoilRepository;
use App\Repository\SpoilShareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SpoilController extends AbstractController
{
    /**
     * @Route("/spoils", name="spoils", options={"sitemap"="true"})
     * @param SpoilRepository $repo
     * @return HttpFoundationResponse
     */
    public function showSpoils(SpoilRepository $repo)
    {
        return $this->render('spoils/list.html.twig', [
            'spoils' => $repo->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/spoil/{id}", name="show_spoil", options={"sitemap"="true", "sitemap_entity_repo"="Spoil"})
     * @Route("/spoil/{title}/{id}", name="show_spoil_title", options={"sitemap"="true", "sitemap_entity_repo"="Spoil"})
     * @param Spoil $spoil
     * @return HttpFoundationResponse
     */
    public function showSpoilDetail(Spoil $spoil)
    {
        return $this->render('spoils/show.html.twig', [
            'spoil' => $spoil
        ]);
    }

    /**
     * @Route("add/spoil/share/{spoil_id}/{media_type}", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @ParamConverter("spoil", options={"mapping": {"spoil_id": "id"}})
     * @ParamConverter("socialmedia_type", options={"mapping": {"media_type": "name"}})
     */
    public function postSpoilShare(Request $request, Spoil $spoil, SocialmediaType $socialmedia_type, EntityManagerInterface $em, SpoilShareRepository $repo)
    {
        $content = json_decode($request->getContent(), true);

        //check params
        if (!isset($content['user']) || !isset($content['spoil']) || !isset($content['type'])) {
            return $this->json([], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }
        if ($this->getUser()->getId() != $content['user']) {
            return $this->json([], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        //check not already share
        $current_share_for_user = $repo->findBy([
            'user' => $this->getUser(),
            'social' => $socialmedia_type
        ]);

        if (count($current_share_for_user) > 0) {
            return $this->json(['reason' => 'already'], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        //post
        $share = new SpoilShare();
        $share->setUser($this->getUser());
        $share->setSpoil($spoil);
        $share->setSocial($socialmedia_type);
        $em->persist($share);
        $em->flush();

        return $this->json(['res' => 'ok']);
    }
}
