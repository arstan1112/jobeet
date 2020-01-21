<?php

namespace App\Service;

use App\Entity\Jobs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobHistoryService
{
    private const MAX = 3;

    /** @var SessionInterface */
    private $session;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param SessionInterface $session
     * @param EntityManagerInterface $em
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session=$session;
        $this->em=$em;
    }

    /**
     * @param Jobs $job
     *
     * @return void
     */
    public function addJob(Jobs $job): void
    {
        // add job to session
        $jobs = $this->getJobIds();

        //Add job id to the beginning of the array
        array_unshift($jobs, $job->getId());

        // Remove duplication of id
        $jobs = array_unique($jobs);

        // Get only first 3 elements
        $jobs = array_slice($jobs, 0, self::MAX);

        // Store id in the session
        $this->session->set('job_history', $jobs);
    }

    /**
     * @return array
     */
    public function getJobIds(): array
    {
        return $this->session->get('job_history', []);
    }

    /**
     * @return Jobs[]
     *
     * @throws NonUniqueResultException
     */
    public function getJobs(): array
    {
        // get job from session
        $jobs = [];
        $jobRepository = $this->em->getRepository(Jobs::class);

        foreach ($this->getJobIds() as $jobId) {
            $jobs[] = $jobRepository->findActiveJob($jobId);
//            $jobs[] = $jobRepository->getPaginatedActiveJobsByCategoryQuery($jobId);
        }

        return array_filter($jobs);
    }
}
