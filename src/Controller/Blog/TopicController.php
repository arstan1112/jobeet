<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Entity\User;
use App\Form\Blog\TopicType;
use App\Service\BlogTopicCreator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use PhpScience\TextRank\TextRankFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpScience\TextRank\Tool\StopWords\English;
use App\Service\BlogHashTagService;

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
     * @param  int                $page
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page) : Response
    {
        $topics = $paginator->paginate(
            $this->getDoctrine()->getRepository(BlogTopic::class)->findRecentTopics(),
            $page,
            $this->getParameter('max_per_page')
        );
        return $this->render('blog/topic/list.html.twig', [
            'topics' => $topics,
        ]);
    }

    /**
     * @Route(
     *     "/blog/{id}/{hashTagId}",
     *     name         ="blog.show",
     *     methods      ="GET",
     *     requirements ={"id" = "\d+"},
     *     defaults     ={"id":1, "hashTagId":0}
     *     )
     * @param  BlogTopic $blogTopic
     * @param  int       $hashTagId
     * @return Response
     */
    public function show(BlogTopic $blogTopic, int $hashTagId) : Response
    {
        return $this->render('blog/topic/show.html.twig', [
            'topic'     => $blogTopic,
            'hashTagId' => $hashTagId,
        ]);
    }

    /**
     * @Route("blog/topic/create/", name="blog.topic.create", methods={"GET", "POST"})
     * @param Request          $request
     * @param BlogTopicCreator $topicCreator
     * @return Response
     */
    public function create(Request $request, BlogTopicCreator $topicCreator) : Response
    {
        $topic = new BlogTopic();
        $form  = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->getUser();
                $topicCreator->create($topic, $user);
            } catch (\Exception $e) {
                return $this->render('error.html.twig', [
                    'error_message' => $e->getMessage(),
                ]);
            }

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
