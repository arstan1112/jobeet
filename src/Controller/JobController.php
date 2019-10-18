<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\JobsRepository;
use App\Repository\CategoriesRepository;
use App\Form\JobType;
use App\Entity\Jobs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;

///**
// * @Route("job")
// */

class JobController extends AbstractController
{

    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @return Response
     */
//    public function list(EntityManagerInterface $em) : Response
    public function list(EntityManagerInterface $em) : Response
    {
//        $jobs = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
//        $jobs = $em->getRepository(Jobs::class)->findActiveJobs();
//        $categories = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
        $categories = $em->getRepository(Categories::class)->findWithActiveJobs();
//        $jobs = $repository->findActiveJobs();
//        $categories = $repository->findWithActiveJobs();
        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"}, defaults={"id":1})
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

    // ...

    /**
     * Creates a new job entity.
     *
     * @Route("job/create", name="job.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader) : Response
    {
        $job = new Jobs();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
//                $fileName = \bin2hex(\random_bytes(10)) . '.' . $logoFile->guessExtension();
                // moves the file to the directory where brochures are stored
//                $logoFile->move(
//                    $this->getParameter('jobs_directory'),
//                    $fileName
//                );

                $fileName = $fileUploader->upload($logoFile);
                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('job.list');
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
