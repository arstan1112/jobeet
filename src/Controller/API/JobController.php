<?php


namespace App\Controller\API;

use App\Entity\Affiliates;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Jobs;
use Doctrine\ORM\EntityManagerInterface;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
//use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Response;


class JobController extends AbstractFOSRestController
//class JobController extends FOSRestController
{
    /**
     * @Rest\Get("/{token}/jobs", name="api.job.list")
     *
     * @Entity("affiliate", expr="repository.findOneActiveByToken(token)")
     * @param Affiliates $affiliate
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function getJobsAction(Affiliates $affiliate, EntityManagerInterface $em) : Response
//    public function getJobsAction(EntityManagerInterface $em) : Response
    {
//        $jobs = $em->getRepository(Jobs::class)->findActiveJobs();
        $jobs = $em->getRepository(Jobs::class)->findActiveJobsForAffiliate($affiliate);

        return $this->handleView($this->view($jobs, Response::HTTP_OK));
    }

}