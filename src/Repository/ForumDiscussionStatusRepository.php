<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumDiscussionStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumDiscussionStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumDiscussionStatus[]    findAll()
 * @method ForumDiscussionStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumDiscussionStatusRepository extends ServiceEntityRepository
{
    public const OUVERT_ID = 1;
    public const FERMER_ID = 2;
    public const ACCEPTER_ID = 3;
    public const REFUSER_ID = 4;
    public const EN_ATTENTE_ID = 5;
    private $accept_staff_only_ids;
    private $normal_discussion_ids;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumDiscussionStatus::class);
        $this->accept_staff_only_ids = [
            ForumDiscussionStatusRepository::EN_ATTENTE_ID,
            ForumDiscussionStatusRepository::REFUSER_ID,
            ForumDiscussionStatusRepository::ACCEPTER_ID,
        ];
        $this->normal_discussion_ids = [
            ForumDiscussionStatusRepository::OUVERT_ID,
            ForumDiscussionStatusRepository::FERMER_ID,
        ];
    }

    public function createStatusQueryBuilder($discussion_staff_only)
    {
        $qb = $this->createQueryBuilder('s');

        $id_list = [];
        /** @var ForumDiscussion $discussion_staff_only */
        if ($discussion_staff_only->getSubCategory()->getAcceptStaffOnly()) {
            $id_list = $this->accept_staff_only_ids;
        } else {
            $id_list = $this->normal_discussion_ids;
        }

        foreach ($id_list as $key => $id) {
            $qb->orWhere('s.id = :id_' . $key)
                ->setParameter('id_' . $key, $id);
        }

        return $qb;
    }
}
