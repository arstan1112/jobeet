<?php


namespace App\Controller\Admin;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use App\Controller\VisitInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Affiliates;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\MailerService;

class AffiliateController extends AbstractController implements VisitInterface
{
    /**
     * List all affiliate entities
     *
     * @Route("/admin/affiliates/{page}",
     *     name="admin.affiliates.list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param int $page
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page) : Response
    {
        $affiliates = $paginator->paginate(
            $em->getRepository(Affiliates::class)->createQueryBuilder('a'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'a.active',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'ASC',
            ]
        );

        return $this->render('admin/affiliate/list.html.twig', [
            'affiliates' => $affiliates,
        ]);
    }

    /**
     * Activate affiliate
     *
     * @Route("/admin/affiliate/{id}/activate",
     *     name="admin.affiliate.activate",
     *     methods="GET",
     *     requirements={"id" = "\d+"}
     * )
     *
     * @param EntityManagerInterface $em
     * @param Affiliates $affiliate
     * @param MailerService $mailerService
     *
     * @return Response
     */
    public function activate(EntityManagerInterface $em, Affiliates $affiliate, MailerService $mailerService) : Response
    {
        $affiliate->setActive(true);
        $em->flush();

        $mailerService->sendActivationEmail($affiliate);

        return $this->redirectToRoute('admin.affiliates.list');
    }

    /**
     * Deactivate affiliate
     *
     * @Route("/admin/affiliate/{id}/deactivate",
     *     name="admin.affiliate.deactivate",
     *     methods="GET",
     *     requirements={"id" = "\d+"}
     * )
     *
     * @param EntityManagerInterface $em
     * @param Affiliates $affiliate
     *
     * @return Response
     */
    public function deactivate(EntityManagerInterface $em, Affiliates $affiliate) : Response
    {
        $affiliate->setActive(false);
        $em->flush();

        return $this->redirectToRoute('admin.affiliates.list');
    }

}