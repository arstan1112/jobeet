<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Affiliates;
use App\Form\AffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class AffiliateController extends AbstractController
{
    /**
     * Creates a new affiliate entity
     *
     * @Route("/affiliate/create", name="affiliate.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $affiliate = new Affiliates();
        $form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliate->setActive(false);

            $em->persist($affiliate);
            $em->flush();

            return $this->redirectToRoute('affiliate.wait');
        }

        return $this->render('affiliate/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Shows the wait affiliate message
     *
     * @Route("/affiliate/wait", name="affiliate.wait", methods={"GET"})
     *
     * @return Response
     */
    public function wait()
    {
        return $this->render('affiliate/wait.html.twig');
    }

}