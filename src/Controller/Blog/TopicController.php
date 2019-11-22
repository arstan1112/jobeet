<?php

namespace App\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @Route("/blog/topic", name="blog_topic")
     */
    public function index()
    {

        return $this->render('blog/topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }
}
