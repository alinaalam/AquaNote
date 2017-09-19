<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderedByRecentlyActive()
    {
        //ordering by on the basis of createdAt prop that's in another table
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
            ->orderBy('genus_note.createdAt', 'DESC')
            ->getQuery()
            ->execute();
        /**
         * here 'genus' is the alias
         * the repo already knows the table name
         * returns back the list of genus in desc order of their speciesCount
        **/
//        return $this->createQueryBuilder('genus')
//            ->andWhere('genus.isPublished = :isPublished')
//            ->setParameter('isPublished', true)
//            ->orderBy('genus.speciesCount', 'DESC')
//            ->getQuery()
//            ->execute(); //execute() for array of results, getOneNullOrResult() for one or null if none matched
    }
}