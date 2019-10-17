<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Jobs;
use App\Repository\JobsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{

    /**
     * Finds and displays a category entity.
     *
     * @Route("/category/{slug}", name="category.show", methods="GET")
     *
     * @param Categories $category
     * @param PaginatorInterface $paginator
     *
     *
     * @return Response
     */
    public function show(Categories $category, PaginatorInterface $paginator) : Response
    {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Jobs::class)->getPaginatedActiveJobsByCategoryQuery($category),
//            $this->$repository->getPaginatedActiveJobsByCategoryQuery($category),
//            $this->$repository->getPaginatedActiveJobsByCategoryQuery($category),
            1, // page
            10 // elements per page
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'activeJobs' => $activeJobs,
        ]);
    }

}
