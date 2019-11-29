<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
use App\Entity\User;
use App\Form\Blog\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TopicController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/blog/list", name="blog.list")
     */
    public function list() : Response
    {
        $topics = $this->em->getRepository(BlogTopic::class)->findAll();
        return $this->render('blog/topic/list.html.twig', [
            'topics' => $topics,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog.show", methods="GET", requirements={"id" = "\d+"}, defaults={"id":1})
     * @param BlogTopic $blogTopic
     * @return Response
     */
    public function show(BlogTopic $blogTopic) : Response
    {
        return $this->render('blog/topic/show.html.twig', [
            'topic' => $blogTopic,
        ]);
    }

    /**
     * @Route("blog/topic/create/", name="blog.topic.create", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request) : Response
    {
        $topic = new BlogTopic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $user  = $this->em->getRepository(User::class)->find($userId);
            $topic->setCreatedAt(new \DateTime());
            $topic->setUpdatedAt(new \DateTime());
            $topic->setAuthor($user);
            $this->em->persist($topic);
            $this->em->flush();

            return $this->redirectToRoute(
                'blog.list'
            );
        }

        return $this->render('blog/topic/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
