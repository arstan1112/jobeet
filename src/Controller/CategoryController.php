<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Jobs;
use App\Repository\JobsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\JobHistoryService;

class CategoryController extends AbstractController
//class CategoryController extends Controller
{

//    /**
//     * Finds and displays a category entity.
//     *
//     * @Route("/category/{slug}", name="category.show", methods="GET")
//     *
//     * @param Categories $category
//     * @param PaginatorInterface $paginator
//     *
//     *
//     * @return Response
//     */
//    public function show(Categories $category) : Response
//    {
//        $activeJobs = $paginator->paginate(
//            $this->getDoctrine()->getRepository(Jobs::class)->getPaginatedActiveJobsByCategoryQuery($category),
//            $this->$repository->getPaginatedActiveJobsByCategoryQuery($category),
//            $this->$repository->getPaginatedActiveJobsByCategoryQuery($category),
//            1, // page
//            10 // elements per page
//        );

//        return $this->render('category/show.html.twig', [
//            'category' => $category,
//            'activeJobs' => $activeJobs,
//        ]);
//    }

    /**
     * Finds and displays a category entity.
     *
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="category.show",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"})
     *
     * @param Categories $category
     * @param PaginatorInterface $paginator
     * @param int $page
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     */
    public function show(
        Categories $category,
        PaginatorInterface $paginator,
        int $page,
        JobHistoryService $jobHistoryService
    ) : Response  {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Jobs::class)->getPaginatedActiveJobsByCategoryQuery($category),
            $page,
            $this->getParameter('max_jobs_on_category')
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'activeJobs' => $activeJobs,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }

}
