<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Genus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]);
        /**
         * harcoding stuff
         */
        /*$genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(rand(100, 99999));
        $manager->persist($genus);
        $manager->flush();*/
    }

    public function genus()
    {
        $genera = [
            'Octopus',
            'Balaena',
            'Orcinus',
            'Hippocampus',
            'Asterias',
            'Amphiprion',
            'Carcharodon',
            'Aurelia',
            'Cucumaria',
            'Balistoides',
            'Paralithodes',
            'Chelonia',
            'Trichechus',
            'Eumetopias'
        ];
        $key = array_rand($genera);
        return $genera[$key];
    }

    public function subFamily()
    {
        $familia = [
            'OctopusFamilia',
            'BalaenaFamilia',
            'OrcinusFamilia',
            'HippocampusFamilia',
            'AsteriasFamilia',
            'AmphiprionFamilia',
            'CarcharodonFamilia',
            'AureliaFamilia',
            'CucumariaFamilia',
            'BalistoidesFamilia',
            'ParalithodesFamilia',
            'CheloniaFamilia',
            'TrichechusFamilia',
            'EumetopiasFamilia'
        ];
        $key = array_rand($familia);
        return $familia[$key];
    }
}