<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderedBySize()
    {
        /**
         * here 'genus' is the alias
         * the repo already knows the table name
        **/
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->orderBy('genus.speciesCount', 'DESC')
            ->getQuery()
            ->execute(); //execute() for array of results, getOneNullOrResult() for one or null if none matched
    }
}