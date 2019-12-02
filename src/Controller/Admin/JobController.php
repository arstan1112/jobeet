<?php

namespace App\Controller\Admin;

use App\Controller\VisitInterface;
use App\Entity\Jobs;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

class JobController extends AbstractController implements VisitInterface
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Lists all jobs entities
     *
     * @Route("/admin/jobs/{page}",
     *     name="admin.job.list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     *     )
     *
     * @param PaginatorInterface $paginator
     * @param int $page
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page) : Response
    {
        $jobs=$paginator->paginate(
            $this->em->getRepository(Jobs::class)->createQueryBuilder('j'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'j.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'DESC',
            ]
        );

        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Create job
     *
     * @Route("/admin/job/create", name="admin.job.create", methods="GET|POST")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader) : Response
    {
        $job  = new Jobs();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var UploadedFile $uploadedFile */
//            $uploadedFile = $job->getLogo();
//            $uploadedFile->move('', '');
            /** @var UploadedFile|null $logofile */
            $logofile = $form->get('logo')->getData();

            if ($logofile instanceof UploadedFile) {
                $filename = $fileUploader->upload($logofile);
                $job->setLogo($filename);
            }
            $this->em->persist($job);
            $this->em->flush();

            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit job
     *
     * @Route("/admin/job/{id}/edit", name="admin.job.edit", methods="GET|POST", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Jobs $job
     * @return Response
     */
    public function edit(Request $request, Jobs $job) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a job
     *
     * @Route("/admin/job/{id}/delete", name="admin.job.delete", methods="DELETE", requirements={"id" = "\d+"})
     *
     * @param  Request $request
     * @param  Jobs $job
     * @return Response
     */
    public function delete(Request $request, Jobs $job) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $this->em->remove($job);
            $this->em->flush();
        }
        return $this->redirectToRoute('admin.job.list');
    }
}
