<?php


namespace App\Controller\API;

use App\Controller\FormErrorsTrait;
use App\Entity\Affiliates;
use App\Form\JobType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Jobs;
use Doctrine\ORM\EntityManagerInterface;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
//use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Rest\Get("/api/v1/{token}/jobs", name="api.job.list")
     *
     * @Entity("affiliate", expr="repository.findOneActiveByToken(token)")
     *
     * @param Affiliates $affiliate
     * @param EntityManagerInterface $em
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
     * @param Request $request
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Rest\Post("/api/v1/jobs", name="api.job.post")
     */
    public function postJob(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $job = $serializer->deserialize(
            $request->getContent(), Jobs::class, 'json'
        );
//        dump($job);
//        die();

        $data = json_decode($request->getContent(), true);


        $form = $this->createForm(JobType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($data);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->json([
                'status' => 'success',
            ]);
        }

        return $this->json([
            'status' => 'error',
            'errors' => $this->getErrorsFromForm($form),
        ], 400);

//        $errors = $validator->validate($job);
//        if (count($errors) > 0) {
//            $response = [];
//            foreach ($errors as $error) {
//                /** @var ConstraintViolation $error */
//                $response[] = [
//                    'name' => $error->getPropertyPath(),
//                    'message' => $error->getMessage()
//                ];
//            }
//
//
//            return $this->json([
//                'status' => 'error',
//                'errors' => $response,
//            ], 400);
//        }
    }


}