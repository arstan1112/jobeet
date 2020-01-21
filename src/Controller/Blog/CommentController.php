<?php


namespace App\Controller\Blog;

use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use App\Entity\User;
use App\Form\Blog\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
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
     * @Route("blog/comment/create/{id}", name="blog.comment.create", methods={"GET", "POST"})
     *
     * @param Request   $request
     * @param BlogTopic $blogTopic
     *
     * @return Response
     *
     * @throws Exception
     */
    public function create(Request $request, BlogTopic $blogTopic): Response
    {
        $comment = new BlogComment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $user   = $this->em->getRepository(User::class)->find($userId);
            $comment->setBlogTopic($blogTopic);
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($user);
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute(
                'blog.show',
                ['id' => $blogTopic->getId()]
            );
        }

        return $this->render('blog/comments/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "blog/comment/{id}/{text}",
     *     name         = "blog.comment.add",
     *     methods      = {"GET", "POST"},
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @param BlogTopic $topic
     * @param string    $text
     *
     * @return Response
     *
     * @throws Exception
     */
    public function add(BlogTopic $topic, string $text): Response
    {
        $comment = new BlogComment();
        $comment->setText($text);
        $comment->setCreatedAt(new \DateTime());
        $comment->setUser($this->getUser());

        $topic->addBlogComments($comment);

        $this->em->persist($topic);
        $this->em->persist($comment);
        $this->em->flush();

        $comments   = [];
        $comments[] = $comment;

        $rendered = $this->renderView('blog/comments/comments.html.twig', [
            'comments' => $comments,
        ]);

        return $this->json([
            'content' => $rendered,
        ]);
    }

    /**
     * @Route(
     *     "blog/comments/up/{id}/{counter}",
     *     name         = "blog.comments.up",
     *     methods      = {"GET", "POST"},
     *     requirements = {"id"="\d+", "counter"="\d+"}
     *     )
     *
     * @param int $id
     * @param int $counter
     *
     * @return Response
     */
    public function addInScroll(int $id, int $counter): Response
    {
        $comments = $this
            ->em
            ->getRepository(BlogComment::class)
            ->findByTopicId($id, $counter);

        $rendered = $this->renderView('blog/comments/comments.html.twig', [
            'comments' => $comments,
        ]);

        if (!$comments) {
            return $this->json(['message' => 'All comments rendered.'], 202);
        }

        return $this->json([
            'content' => $rendered,
        ], 201);
    }
}
