<?php


namespace App\Controller\Admin;

use App\Entity\BlogTopicHashTag;
use App\Form\Blog\HashTagSearchType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogHashTagController extends AbstractController
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
     * @Route("admin/blog/hashtags/list", name="admin.blog.hashtags.list", methods={"GET"})
     */
    public function list() : Response
    {
        $hashTags = $this->em->getRepository(BlogTopicHashTag::class)->findAll();
        return $this->render('admin/blog/hashtags/list.html.twig', [
           'hashTags' => $hashTags,
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/hashtag/edit/{id}",
     *     name         = "admin.blog.hashtag.edit",
     *     methods      = {"GET", "POST"},
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @param Request          $request
     * @param BlogTopicHashTag $hashTag
     *
     * @return Response
     */
    public function edit(Request $request, BlogTopicHashTag $hashTag): Response
    {
        $form = $this->createForm(HashTagSearchType::class, $hashTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin.blog.hashtags.list');
        }

        return $this->render('admin/blog/hashtags/edit.html.twig', [
            'hashTag' => $hashTag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "admin/blog/hashtag/delete/{id}",
     *     name         = "admin.blog.hashtag.delete",
     *     methods      = {"DELETE"},
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @param Request          $request
     * @param BlogTopicHashTag $hashTag
     *
     * @return Response
     */
    public function delete(Request $request, BlogTopicHashTag $hashTag): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hashTag->getId(), $request->request->get('_token'))) {
            $this->em->remove($hashTag);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin.blog.hashtags.list');
    }
}
