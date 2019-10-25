<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\CategoryType;

class CategoryController extends AbstractController
{
    /**
     * Lists all categories entities.
     *
     * @Route("/admin/categories", name="admin.category.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
        $categories = $em->getRepository(Categories::class)->findAll();

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Create category.
     *
     * @Route("/admin/category/create", name="admin.category.create", methods="GET|POST")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit category
     *
     * @Route("/admin/category/{id}/edit", name="admin.category,edit", methods="GET|POST", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Categories $category
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em, Categories $category) : Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category'=> $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete category
     *
     * @Route("/admin/category/{id}/delete", name="admin.category.delete", methods="DELETE", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Categories $category
     *
     * @return Response
     *
     */
    public function delete(Request $request, EntityManagerInterface $em, Categories $category) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin.category.list');
    }
}