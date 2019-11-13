<?php


namespace App\Controller\API;

use App\Controller\FormErrorsTrait;
use App\Entity\Affiliates;
use App\Form\JobType;
use App\Service\FileUploader;
use App\Service\JobSaveService;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Jobs;
use Doctrine\ORM\EntityManagerInterface;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
//use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Monolog\Logger;
use PhpParser\Node\Scalar\MagicConst\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\API\JobUploadApi;
use Psr\Log\LoggerInterface;

///**
// * @Route("/api/v1/")
// */

class JobController extends AbstractFOSRestController
{
    use FormErrorsTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @Rest\Get("/api/v1/{token}/jobs", name="api.job.list")
     *
     * @Entity("affiliate", expr="repository.findOneActiveByToken(token)")
     *
     * @param Affiliates $affiliate
     *
     * @return Response
     */
    public function getJobsAction(Affiliates $affiliate) : Response
//    public function getJobsAction(EntityManagerInterface $em) : Response
    {
//        $jobs = $em->getRepository(Jobs::class)->findActiveJobs();
        $jobs = $this->em->getRepository(Jobs::class)->findActiveJobsForAffiliate($affiliate);

        return $this->handleView($this->view($jobs, Response::HTTP_OK));
    }

    /**
     * @param Request            $request
     * @param ValidatorInterface $validator
     * @param JobSaveService     $jobService
     *
     * @Rest\Post("api/v1/jobsupl", name="api.jobsupl.post")
     *
     * @return JsonResponse
     */
    public function postJobUpload(Request $request, JobSaveService $jobService, ValidatorInterface $validator)
    {
        $uploadApi = $this->serializer->deserialize(
            $request->getContent(),
            Jobs::class,
            'json'
        );

        $errors = $validator->validate($uploadApi);
        if (count($errors) > 0) {
            $response = [];
            foreach ($errors as $error) {
                /** ConstraintViolation $error */
                $response[] = [
                    'name' => $error->getPropertyPath(),
                    'message' => $error->getMessage()
                ];
            }
            return $this->json([
                'status' => 'error',
                'errors' => $response,
            ], 400);
        }

        $status = 201;

        try {
            $jobService->saveJob($uploadApi);

            $response = ['status' => 'success', 'message' => 'Entry success'];

//            throw new \Exception('test_message');

        } catch (\Exception $e) {
//            $this->logger->info($e->getMessage());
            $status = 400;
            $response = [
                'status' => 'ErrorException',
                'message' => $e->getMessage(),
            ];
        }

        return $this->json($response, $status);

    }

//    /**
//     * @param Request $request
//     *
//     * @param SerializerInterface $serializer
//     * @param ValidatorInterface $validator
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     * @Rest\Post("/api/v1/jobs", name="api.job.post")
//     */
//    public function postJob(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
//    {
//        $data = json_decode($request->getContent(), true);
//        $form = $this->createForm(JobType::class, null, [
//            'csrf_protection' => false,
//        ]);
//
//        $form->submit($data);
//
//        if ($form->isSubmitted() and $form->isValid()) {
//            $this->em->persist($form->getData());
//            $this->em->flush();
//
//            return $this->json([
//                'status' => 'success',
//            ]);
//        }
//
//        return $this->json([
//            'status' => 'error',
//            'errors' => $this->getErrorsFromForm($form),
//        ], 400);
//
//    }

}