<?php

namespace App\Controller;

use App\Entity\Categories;
use App\EventListener\Event\VisitCreatedEvent;
use App\Repository\JobsRepository;
use App\Repository\CategoriesRepository;
use App\Form\JobType;
use App\Entity\Jobs;
use App\Service\FileUploader;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormInterface;
use App\Service\JobHistoryService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

///**
// * @Route("job")
// */

class JobController extends AbstractController implements VisitInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

//    /**
//     * @var Request
//     */
//    private $request;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param Request $request
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em         = $em;
        $this->dispatcher = $dispatcher;
//        $this->request    = $request;
    }

    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     * @throws NonUniqueResultException
     * @throws \Psr\Cache\InvalidArgumentException
     */
//    public function list(EntityManagerInterface $em) : Response
    public function list(JobHistoryService $jobHistoryService) : Response
    {
//        $cache = new FilesystemAdapter();
//        $cache->delete('test_caching');
//        $cache->get('test_caching', function (ItemInterface $item) {
//            $item->expiresAfter(5);
//            $computedValue = 'new_foobar_value';
//            return $computedValue;
//        });

//        $jobs = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
//        $jobs = $em->getRepository(Jobs::class)->findActiveJobs();
//        $categories = $this->getDoctrine()->getRepository(Jobs::class)->findAll();
        $categories = $this->em->getRepository(Categories::class)->findWithActiveJobs();
//        $jobs = $repository->findActiveJobs();
//        $categories = $repository->findWithActiveJobs();
//        dump($jobHistoryService->getJobs());
//        die;

//        $event = new VisitCreatedEvent('job_list');
//        $this->dispatcher->dispatch($event);

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"}, defaults={"id":40})
     *
     * @Entity("jobs", expr="repository.findActiveJob(id)")
     *
     * @param Jobs $job
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(Jobs $job, JobHistoryService $jobHistoryService) : Response
    {
//        $cache = new FilesystemAdapter();
//        $value = $cache->get('test_caching', function (ItemInterface $item) {
//            $item->expiresAfter(3600);
//            $computedValue = 'no_value';
//
//            return $computedValue;
//        });
//        dump($value);
//        die();

        $jobHistoryService->addJob($job);
//        $event = new VisitCreatedEvent('job_show');
//        $this->dispatcher->dispatch($event);

        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * Creates a new job entity.
     *
     * @Route("job/create", name="job.create", methods={"GET", "POST"})
     *
     * @param FileUploader $fileUploader
     * @param Request      $request
     *
     * @return Response
     */
    public function create(FileUploader $fileUploader, Request $request) : Response
    {
        $job = new Jobs();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var UploadedFile|null $logoFile */
//            $logoFile = $form->get('logo')->getData();
//            if ($logoFile instanceof UploadedFile) {
//                $fileName = $fileUploader->upload($logoFile);
//                $job->setLogo($fileName);
//            }

            $this->em->persist($job);
            $this->em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        $event = new VisitCreatedEvent('job_create');
        $this->dispatcher->dispatch($event);

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing job entity
     *
     * @Route("/job/{token}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     *
     * @param Jobs    $job
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Jobs $job, Request $request) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

//            return $this->redirectToRoute('job.list');
            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("job/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Jobs $job
     *
     * @return Response
     */
    public function preview(Jobs $job) : Response
    {
        $deleteForm = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
            'publishForm' => $publishForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Jobs $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Jobs $job) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="job.delete", methods="DELETE", requirements={"token" = "\w+"})
     *
     * @param Jobs    $job
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Jobs $job, Request $request) : Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($job);
            $this->em->flush();
        }

        return $this->redirectToRoute('job.list');
    }

    /**
     * Publish a job entity.
     *
     * @Route("job/{token}/publish", name="job.publish", methods="POST", requirements={"token" = "\w+"})
     *
     * @param Jobs    $job
     * @param Request $request
     *
     * @return Response
     */
    public function publish(Jobs $job, Request $request) : Response
    {
        $form = $this->createPublishForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);

            $this->em->flush();

            $this->addFlash('notice', 'Your job was published');
        }

        return $this->redirectToRoute('job.preview', [
            'token' => $job->getToken(),
        ]);
    }

    /**
     * Creates a form to publish a job entity.
     *
     * @param Jobs $job
     *
     * @return FormInterface
     */
    private function createPublishForm(Jobs $job) : FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
            ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
            ->setMethod('POST')
            ->getForm();
    }

}
