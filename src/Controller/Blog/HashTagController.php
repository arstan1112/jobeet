<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HashTagController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route(
     *     "blog/hashtags/list",
     *     name         ="blog.hashtags.list",
     *     methods      ={"GET"},
     *     defaults     ={"page":1},
     *     requirements ={"page" = "\d+"}
     *     )
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page) : Response
    {
        $hashTags = $paginator->paginate(
            $this->em->getRepository(BlogTopicHashTag::class)->findAll(),
            $page,
            $this->getParameter('max_per_page')
        );
        return $this->render('blog/hashtags/list.html.twig', [
            'hashTags' => $hashTags,
        ]);
    }

    /**
     * @Route(
     *     "blog/hash/{id}/show/{page}/{hashTagList}",
     *     name         ="blog.hash.show",
     *     methods      ={"GET"},
     *     requirements ={"id" = "\d+"},
     *     defaults     ={"page":1, "hashTagList":0}
     *     )
     * @param PaginatorInterface $paginator
     * @param BlogTopicHashTag   $hashTag
     * @param int                $page
     * @param int                $hashTagList
     *
     * @return Response
     */
    public function show(
        PaginatorInterface $paginator,
        BlogTopicHashTag $hashTag,
        int $page,
        int $hashTagList
    ) : Response {
        $topicsOfHashTag = $paginator->paginate(
            $this->getDoctrine()->getRepository(BlogTopic::class)->findRecentTopicsByHashTag($hashTag),
            $page,
            $this->getParameter('max_per_page')
        );
        return $this->render('blog/hashtags/show.html.twig', [
            'hashTag'         => $hashTag,
            'topicsOfHashTag' => $topicsOfHashTag,
            'hashTagList'     => $hashTagList,
        ]);
    }
}
