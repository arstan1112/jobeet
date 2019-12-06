<?php

namespace App\Controller\Admin;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Entity\User;
use App\Form\Admin\BlogType;
use App\Form\Blog\TopicType;
use App\Service\BlogHashTagChecker;
use App\Service\BlogHashTagService;
use App\Utils\HashTagsNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\Annotations as Rest;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BlogController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("admin/blog/topics/list", name="admin.blog.topics.list", methods="GET")
     * @return Response
     */
    public function list() : Response
    {
        $topics = $this->em->getRepository(BlogTopic::class)->findAll();

        return $this->render('admin/blog/list.html.twig', [
            'topics' => $topics,
        ]);
    }

    /**
     * @Route("admin/blog/topic/create", name="admin.blog.topic.create", methods={"GET", "POST"})
     * @param  Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request) : Response
    {
        $topic = new BlogTopic();
        $form  = $this->createForm(BlogType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $user   = $this->em->getRepository(User::class)->find($userId);
            $topic->setCreatedAt(new \DateTime());
            $topic->setUpdatedAt(new \DateTime());
            $topic->setAuthor($user);
            $this->em->persist($topic);
            $this->em->flush();

            return $this->redirectToRoute('admin.blog.topics.list');
        }

        return $this->render('admin/blog/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/topic/{id}/edit",
     *     name="admin.blog.topic.edit",
     *     methods={"GET", "POST"},
     *     requirements={"id"="\d+"}
     *     )
     * @param Request $request
     * @param BlogTopic $topic
     * @param BlogHashTagChecker $hashTagService
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function edit(Request $request, BlogTopic $topic, BlogHashTagChecker $hashTagService): Response
    {
//        $form = $this->createForm(BlogType::class, $topic);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashTags    = $topic->getHash();
            $checkedTags = $hashTagService->hashTagExist($hashTags);

            $old = $topic->getBlogTopicHashTags();
            foreach ($old->getValues() as $oldTag) {
                $topic->removeBlogTopicHashTag($oldTag);
            }

            foreach ($checkedTags[0] as $newTag) {
                $hashTagObj = new BlogTopicHashTag();
                $hashTagObj->setName($newTag);
                $hashTagObj->setCreatedAt(new \DateTime());
                $topic->addBlogTopicHashTag($hashTagObj);
            };

            foreach ($checkedTags[1] as $existedTag) {
                $topic->addBlogTopicHashTag($existedTag);
            };
            $this->em->flush();

            return $this->redirectToRoute('admin.blog.topics.list');
        }

        return $this->render('admin/blog/edit.html.twig', [
            'topic' => $topic,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/topic/{id}/delete",
     *     name="admin.blog.topic.delete",
     *     methods={"DELETE"},
     *     requirements={"id" = "\d+"}
     *     )
     * @param  Request $request
     * @param  BlogTopic $topic
     *
     * @return Response
     */
    public function delete(Request $request, BlogTopic $topic) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->request->get('_token'))) {
            $this->em->remove($topic);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin.blog.topics.list');
    }
}
