<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'movie')]
    public function index(): Response
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/top_rated?api_key=d2c58004100fdb2c3d65ba0058594263');
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();
        $listMovie = $content['results'];

        dd($listMovie);
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'content',
        ]);
    }
    #[Route('/show/{id}', name: 'movie_show')]
    public function show(): Response
    {

        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
}
