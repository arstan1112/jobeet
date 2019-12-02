<?php


namespace App\Controller\Blog;

use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use App\Entity\User;
use App\Form\Blog\CommentType;
use Doctrine\ORM\EntityManagerInterface;
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
     * BlogCommentController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("blog/comment/create/{id}", name="blog.comment.create", methods={"GET", "POST"})
     * @param Request $request
     * @param BlogTopic $blogTopic
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, BlogTopic $blogTopic) : Response
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
}
