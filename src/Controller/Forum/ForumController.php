<?php

namespace App\Controller\Forum;

use App\Controller\BaseController;
use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumSubCategory;
use Symfony\Component\HttpFoundation\Request;

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
}
