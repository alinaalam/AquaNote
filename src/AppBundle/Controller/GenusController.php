<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class GenusController extends Controller
{
    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        $genus = new Genus();

        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(rand(100, 99999));

        $note = new GenusNote();
        $note->setUsername('AquaWeaver');
        $note->setUserAvatarFilename('ryan.jpeg');
        $note->setNote('I counted 8 legs... as they wrapped around me');
        $note->setCreatedAt(new \DateTime('-1 month'));
        $note->setGenus($genus);

        //save data in the database
        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->persist($note);
        $em->flush();

        return new Response('<html><body>Genus created!</body></html>');
    }

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$genuses = $em->getRepository('AppBundle:Genus')->findAll();
        //using custom query that uses a customized repository
        $genuses = $em->getRepository('AppBundle:Genus')->findAllPublishedOrderedByRecentlyActive();
        //dump($em->getRepository('AppBundle:Genus'));die;
        //dump($genuses);die;
        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
        //$funFact = "Octopuses can change the color of their body in just *three-tenths* of a second!";
        $em = $this->getDoctrine()->getManager();
        $genus = $em->getRepository('AppBundle:Genus')->findOneBy([
            'name' => $genusName
        ]);

        if($genus)
        {
            /*$recentNotes = $genus->getNotes()
                ->filter(function(GenusNote $note){
                    return $note->getCreatedAt() > new \DateTime('-3 months');
                });*/
            //custom query
            $recentNotes = $em->getRepository('AppBundle:GenusNote')->findAllRecentNotesForGenus($genus);
            return $this->render(':genus:show.html.twig', [
                'genus' => $genus,
                'recentNoteCount' => count($recentNotes)
            ]);
        }

        //throw $this->createNotFoundException('genus not found');
        return $this->render(':genus:404.html.twig');
        /*
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);
        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        }
        else {
            sleep(1);
            $funFact = $this->get('markdown.parser')
                ->transform($funFact);
            $cache->save($key, $funFact);
        }
//        $funFact = $this->container->get('markdown.parser')
//            ->transform($funFact);

        return $this->render('genus/show.html.twig', [
            'name' => $genusName,
            'funFact' => $funFact,
        ]);*/
//        $templating = $this->container->get('templating');
//        $html = $templating->render('genus/show.html.twig', [
//            'name' => $genusName
//        ]);
//        return new Response($html);
    }

    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        //dump($genus); die;
        $notes = [];
        $dir = $this->get('kernel')->getRootDir() . '/../web/';
        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => $dir.'images/'.$note->getUserAvatarFilename(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
        //return new Response(json_encode($data));

        //dump($genus->getNotes());die;
    }
}