<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
use App\Entity\User;
use App\Form\Blog\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpScience\TextRank\TextRankFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpScience\TextRank\Tool\StopWords\English;

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
     * @Route("/blog/list/{page}", name="blog.list", defaults={"page":1}, requirements={"page" = "\d+"})
     * @param  PaginatorInterface $paginator
     * @param  int $page
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page) : Response
    {
        $topics = $paginator->paginate(
            $this->getDoctrine()->getRepository(BlogTopic::class)->findRecentTopics(),
//            $this->getDoctrine()->getRepository(BlogTopic::class)->findRecentTopicsWithHashes(),
            $page,
            $this->getParameter('max_per_page')
        );
//        dump($topics);
//        die();
        return $this->render('blog/topic/list.html.twig', [
            'topics' => $topics,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog.show", methods="GET", requirements={"id" = "\d+"}, defaults={"id":1})
     * @param  BlogTopic $blogTopic
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
     * @param  Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request) : Response
    {
        $topic = new BlogTopic();
        $form  = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $user   = $this->em->getRepository(User::class)->find($userId);
            $text   = $topic->getText();

            $api = new TextRankFacade();
            $summary = $api->summarizeTextBasic($text);
            $summary = implode("", $summary);
//            $topic->setCreatedAt(new \DateTime());
//            $topic->setUpdatedAt(new \DateTime());
            $topic->setAuthor($user);
            $topic->setSummary($summary);
            $this->em->persist($topic);
            $this->em->flush();

            return $this->redirectToRoute(
                'blog.show',
                ['id' => $topic->getId()]
            );
        }

        return $this->render('blog/topic/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
