<?php

namespace App\Controller;

use App\Repository\JobsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Jobs;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

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
    public function list(CategoriesRepository $repository) : Response
    {
//        $jobs = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
//        $jobs = $em->getRepository(Jobs::class)->findActiveJobs();
//        $jobs = $repository->findActiveJobs();
        $categories = $repository->findWithActiveJobs();
        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
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
