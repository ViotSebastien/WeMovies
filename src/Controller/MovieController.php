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
        //$client = HttpClient::create();
        $listMovie = $this->client('https://api.themoviedb.org/3/movie/top_rated?api_key=d2c58004100fdb2c3d65ba0058594263');
        /*$contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $listMovie = json_decode($content,true);*/
        foreach ($listMovie['results'] as $key => $value) {
          $movie[] = new Movie();
          $movie[$key]->setId($value["id"]);
          $movie[$key]->setTitle($value["title"]);
          $movie[$key]->setDescription($value["overview"]);
        }
                //  dd($movie);
        return $this->render('movie/index.html.twig', [
            'listMovies' => $movie,
        ]);
    }
    #[Route('/show/{movie_id}', name: 'movie_show')]
    public function show(int $movie_id): Response
    {
        $listMovie = $this->client("https://api.themoviedb.org/3/movie/.$movie_id./videos?api_key=d2c58004100fdb2c3d65ba0058594263");
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'Movies',
        ]);
    }
    public function client($url)
    {
      $client = HttpClient::create();
      $response = $client->request('GET', $url);
      $contentType = $response->getHeaders()['content-type'][0];
      $content = $response->getContent();
      return json_decode($content,true);
    }
}
/**
 *
 */
class Movie
{
  private string $id;
  private string $title;
  private string $description;

  function getId(){
    return $this->id;
  }
  function setId(int $id){
    return $this->id=$id;
  }
  function getTitle(){
    return $this->title;
  }
  function setTitle(string $title){
    return $this->title=$title;
  }
  function getDescription(){
    return $this->description;
  }
  function setDescription(string $description){
    return $this->description=$description;
  }
}
