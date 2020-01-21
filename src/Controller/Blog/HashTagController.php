<?php


namespace App\Controller\Blog;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Form\Blog\HashTagSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
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
     *     name         = "blog.hashtags.list",
     *     methods      = {"GET", "POST"},
     *     defaults     = {"page":1},
     *     requirements = {"page" = "\d+"}
     *     )
     *
     * @param PaginatorInterface $paginator
     * @param int                $page
     * @param Request            $request
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function list(Request $request, PaginatorInterface $paginator, int $page): Response
    {
        $form = $this->createForm(HashTagSearchType::class);
        $form->handleRequest($request);

        $hashTags = $paginator->paginate(
            $this->em->getRepository(BlogTopicHashTag::class)->findAll(),
            $page,
            $this->getParameter('max_per_page')
        );

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getName()) {
                $hashTag = $this->em->getRepository(BlogTopicHashTag::class)->findByName($form->getData()->getName());
                if ($hashTag) {
                    return $this->redirectToRoute(
                        'blog.hash.show',
                        ['id' => $hashTag ->getId()]
                    );
                } else {
                    return $this->render('error.html.twig', [
                        'error_message' => 'Hash Tag not found',
                    ]);
                }
            }

            return $this->render('blog/hashtags/list.html.twig', [
                'hashTags' => $hashTags,
                'form'     => $form->createView(),
            ]);
        }

        return $this->render('blog/hashtags/list.html.twig', [
            'hashTags' => $hashTags,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "blog/hash/{id}/show/{page}/{hashTagList}",
     *     name         = "blog.hash.show",
     *     methods      = {"GET"},
     *     requirements = {"id" = "\d+"},
     *     defaults     = {"page":1, "hashTagList":0}
     *     )
     *
     * @param PaginatorInterface $paginator
     * @param BlogTopicHashTag   $hashTag
     * @param int                $page
     * @param int                $hashTagList
     *
     * @return Response
     */
    public function show(
        PaginatorInterface $paginator,
        BlogTopicHashTag   $hashTag,
        int                $page,
        int                $hashTagList
    ): Response {
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

//    /**
//     * @Route("blog/hashtag/search", name="blog.hashtag.search", methods={"GET", "POST"})
//     *
//     * @param Request $request
//     *
//     * @return Response
//     *
//     * @throws NonUniqueResultException
//     */
//    public function search(Request $request) : Response
//    {
//        $form = $this->createForm(HashTagSearchType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $hashTag = $this->em->getRepository(BlogTopicHashTag::class)->findByName($form->getData()->getName());
//            if ($hashTag) {
//                return $this->redirectToRoute(
//                    'blog.hash.show',
//                    ['id' => $hashTag ->getId()]
//                );
//            } else {
//                return $this->render('error.html.twig', [
//                    'error_message' => 'Hash Tag not found',
//                ]);
//            }
//        }
//
//        return $this->render('blog/hashtags/search.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}
