<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $repository): Response
    {
        $entity = new Post();
        $posts = $entity->getAll($repository);

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
