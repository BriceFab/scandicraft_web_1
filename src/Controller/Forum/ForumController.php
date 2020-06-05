<?php

namespace App\Controller\Forum;

use App\Controller\BaseController;
use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumSubCategory;
use App\Repository\ForumDiscussionStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ForumController extends BaseController
{
    protected static $default_route = 'forum';

    protected function checkAutorized(Request $request, ForumCategory $category, ForumSubCategory $subCategory, $discussion)
    {
        /** @var ForumDiscussion $discussion */
        if (!$category->getActive() || !$subCategory->getActive() || ($discussion != null && $discussion->getArchive())) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
        }

        if (!$subCategory->getWritable()) {
            return $this->retirectToPreviousRoute($request, 'Forum: Vous ne pouvez pas écrire dans cette catégorie', ForumController::$default_route);
        }

        if ($discussion != null && $subCategory->getAcceptStaffOnly() && $discussion->getStaffOnly() && $this->getUser() != $discussion->getCreatedBy()) {
            $this->denyAccessUnlessGranted('ROLE_STAFF');
        }

        if (!$subCategory->getWritable()) {
            return $this->retirectToPreviousRoute($request, 'Forum: Vous ne pouvez pas écrire dans cette catégorie', ForumController::$default_route);
        }
    }

    public function checkDiscussionActionFromStatus(ForumDiscussion $discussion)
    {
        if ($discussion->getStatus() == null) {
            return;
        }

        dd($discussion->getStatus()->getId());

        //les utilisateurs peuvent seulement changer le status en ouvert/fermer
        if ($discussion->getStatus()->getId() != ForumDiscussionStatusRepository::OUVERT_ID) {
            throw new AccessDeniedException("Vous ne pouvez pas changer le status de cette discussion");
        }

        switch ($discussion->getStatus()->getId()) {
            case ForumDiscussionStatusRepository::OUVERT_ID: //ouvert
                break;
            case ForumDiscussionStatusRepository::FERMER_ID: //fermer
                throw new AccessDeniedException("La discussion est fermée. Vous n'êtes pas autorisé à effecter cette action");
                break;
            case ForumDiscussionStatusRepository::ACCEPTER_ID: //accepter
                throw new AccessDeniedException("La candidature est acceptée. Vous n'êtes pas autorisé à effecter cette action");
                break;
            case ForumDiscussionStatusRepository::REFUSER_ID: //refuser
                throw new AccessDeniedException("La candidature est refusée. Vous n'êtes pas autorisé à effecter cette action");
                break;
            case ForumDiscussionStatusRepository::EN_ATTENTE_ID: //en attente
                break;
            default:
                break;
        }
    }
}
