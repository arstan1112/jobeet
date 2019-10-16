<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Jobs;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("job")
 */

class JobController extends AbstractController
{

    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
//        $jobs = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
        $query = $em->createQuery(
            'SELECT j FROM App:Jobs j WHERE j.expiresAt > :date'
        )->setParameter('date', new \DateTime());
        $jobs = $query->getResult();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @param Jobs $job
     *
     * @return Response
     */
    public function show(Jobs $job) : Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }


}
