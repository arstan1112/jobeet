<?php


namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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



}
