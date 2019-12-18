<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
//use App\Entity\BlogTopicHashTag;
//use App\Entity\User;
//use App\Form\Blog\HashTagSearchType;
use App\Form\Blog\TopicType;
use App\Service\BlogTopicCreator;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
//use phpDocumentor\Reflection\DocBlock\Serializer;
//use phpDocumentor\Reflection\Types\This;
//use PhpScience\TextRank\TextRankFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use PhpScience\TextRank\Tool\StopWords\English;
//use App\Service\BlogHashTagChecker;

class TopicController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface     $paginator
     */
    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em        = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route(
     *     "/blog/list/{hashTag}",
     *     name     = "blog.list",
     *     methods  = {"GET", "POST"},
     *     defaults = {"hashTag":""},
     *     )
     * @param Request $request
     * @param string  $hashTag
     *
     * @return Response
     */
    public function list(Request $request, string $hashTag) : Response
    {
        $topicQuery = $this
            ->getDoctrine()
            ->getRepository(BlogTopic::class)
            ->findRecentTopics($hashTag);

        $topics = $this->paginator->paginate(
            $topicQuery,
            $request->query->getInt('page', 1),
            $this->getParameter('max_per_page')
        );

        if ($request->isXmlHttpRequest()) {
            if (count($topics) === 0) {
                return $this->json(['message' => 'No posts found.'], 500);
            }

            $rendered = $this->renderView('blog/topic/table.html.twig', [
                'topics' => $topics,
            ]);

            return $this->json([
                'content' => $rendered,
            ]);
        }

        return $this->render('blog/topic/list.html.twig', [
            'topics' => $topics,
        ]);
    }

    /**
     * @Route(
     *     "/blog/{id}/{hashTagId}",
     *     name         = "blog.show",
     *     methods      = "GET",
     *     requirements = {"id" = "\d+"},
     *     defaults     = {"id":1, "hashTagId":0}
     *     )
     * @param  BlogTopic $blogTopic
     * @param  int       $hashTagId
     *
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
     *
     * @return Response
     */
    public function create(Request $request, BlogTopicCreator $topicCreator) : Response
    {
        $topic = new BlogTopic();
        $form  = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
//            dump($topic);
//            die();
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
