<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Jobs;
use App\Repository\JobsRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\JobHistoryService;

class CategoryController extends AbstractController implements VisitInterface
{
    /**
     * Finds and displays a category entity.
     *
     * @Route(
     *     "/category/{slug}/{page}",
     *     name         = "category.show",
     *     methods      = "GET",
     *     defaults     = {"page": 1},
     *     requirements = {"page" = "\d+"}
     *     )
     *
     * @param Categories         $category
     * @param PaginatorInterface $paginator
     * @param int                $page
     * @param JobHistoryService  $jobHistoryService
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function show(
        Categories         $category,
        PaginatorInterface $paginator,
        int                $page,
        JobHistoryService  $jobHistoryService
    ): Response {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Jobs::class)->getPaginatedActiveJobsByCategory($category),
            $page,
            $this->getParameter('max_jobs_on_category')
        );

        return $this->render('category/show.html.twig', [
            'category'    => $category,
            'activeJobs'  => $activeJobs,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }
}
