<?php


namespace App\Controller\Admin;

use App\Entity\BlogComment;
use App\Entity\User;
use App\Form\Admin\BlogCommentType;
use App\Form\Admin\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogCommentController extends AbstractController
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
     * @Route("admin/blog/comments/list", name="admin.blog.comments.list", methods="GET")
     *
     * @return Response
     */
    public function list() : Response
    {
        $comments = $this->em->getRepository(BlogComment::class)->findAll();

        return $this->render('admin/blog/comments/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/comment/{id}/edit",
     *     name         = "admin.blog.comment.edit",
     *     methods      = {"GET", "POST"},
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @param  Request     $request
     * @param  BlogComment $comment
     *
     * @return Response
     */
    public function edit(Request $request, BlogComment $comment) : Response
    {
        $form = $this->createForm(BlogCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin.blog.comments.list');
        }

        return $this->render('admin/blog/comments/edit.html.twig', [
            'comment' => $comment,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/comment/{id}/delete",
     *     name         = "admin.blog.comment.delete",
     *     methods      = {"DELETE"},
     *     requirements = {"id" = "\d+"}
     *     )
     *
     * @param  Request     $request
     * @param  BlogComment $comment
     *
     * @return Response
     */
    public function delete(Request $request, BlogComment $comment) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $this->em->remove($comment);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin.blog.comments.list');
    }
}
