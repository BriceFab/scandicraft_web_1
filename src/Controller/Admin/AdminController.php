<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $this->em = $this->getDoctrine()->getManagerForClass($this->entity['class']);

        $queryBuilder = $this->em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity');

        /*
            BriceFab: add discriminator type
            https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/inheritance-mapping.html
        */
        $discriminator_name = $this->em->getMetadataFactory()->getMetadataFor($this->entity['class'])->discriminatorValue;  //type disciminator
        $parent_entity = $this->em->getMetadataFactory()->getMetadataFor($this->entity['class'])->rootEntityName;   //entité parent

        if (isset($discriminator_name) && $parent_entity === $this->entity['class']) {
            $queryBuilder
                ->andWhere('TYPE(entity) = :type')
                ->setParameter('type', $discriminator_name);
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.' . $sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }
}
