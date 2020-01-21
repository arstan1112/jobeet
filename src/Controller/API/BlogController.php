<?php


namespace App\Controller\API;

use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractFOSRestController
{
    private const PAGE_LIMIT = 6;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * BlogController constructor.
     *
     * @param EntityManagerInterface $em
     * @param SerializerInterface    $serializer
     * @param PaginatorInterface     $paginator
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface    $serializer,
        PaginatorInterface     $paginator
    ) {
        $this->em         = $em;
        $this->serializer = $serializer;
        $this->paginator  = $paginator;
    }

    /**
     * @Rest\Get("/api/blog/posts", name="api.blog.list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getTopics(Request $request)
    {
        $page = $request->query->get('page');
        if (!$page) {
            $page = 1;
        }

        $topics = $this->paginator->paginate(
            $this->em->getRepository(BlogTopic::class)->createQueryBuilder('b'),
            $page,
            self::PAGE_LIMIT,
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'b.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION  => 'DESC',
            ]
        );

        return $this->handleView(
            $this->view([
                "posts"      => $topics->getItems(),
                "pagination" => [
                    "total"   => $topics->getTotalItemCount(),
                    "page"    => $topics->getCurrentPageNumber(),
                    "perPage" => $topics->getItemNumberPerPage()
                ]
            ], Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Get("/api/blog/posts/{id}", name="api.blog.show", defaults={"id": 1})
     *
     * @param int $id
     *
     * @return Response
     */
    public function getTopic(int $id): Response
    {
        $topic = $this->em->getRepository(BlogTopic::class)->find($id);

        return $this->handleView($this->view($topic, Response::HTTP_OK));
    }

    /**
     * @Rest\Get("/api/blog/posts/{id}/comments", name="api.blog.comments", defaults={"id": 1})
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function getComments(int $id, Request $request): Response
    {
        $page = json_decode($request->getContent(), true)['page'];
        if (!$page) {
            $page = 1;
        }

        $comments = $this->paginator->paginate(
            $this->em->getRepository(BlogComment::class)->findByTopicIdApi($id),
            $page,
            self::PAGE_LIMIT,
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'b.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION  => 'DESC',
            ]
        );

        return $this->handleView(
            $this->view([
                'posts'      => $comments->getItems(),
                'pagination' => [
                    'total'   => $comments->getTotalItemCount(),
                    'page'    => $comments->getCurrentPageNumber(),
                    'perPage' => $comments->getItemNumberPerPage(),
                ]
            ], Response::HTTP_OK)
        );

//        $js = json_decode($request->getContent(), true)['page'];
//        return $this->json([
//           'message' => $js,
//        ]);
    }
}
