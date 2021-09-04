<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\GenreType;

class MovieController extends AbstractController
{
    #[Route('/', name: 'movie')]
    public function index(): Response
    {
        $listMovie = $this->client('https://api.themoviedb.org/3/movie/popular?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR');
        foreach ($listMovie['results'] as $key => $value) {
          $movie[] = new Movie();
          $movie[$key]->setId($value["id"]);
          $movie[$key]->setTitle($value["title"]);
          $movie[$key]->setDescription($value["overview"]);
          $movie[$key]->setImage($value['backdrop_path']);
          $movie[$key]->setAvis($value['vote_average']);
        }
        $movie_id = $movie['0']->getId();
        $promo=$this->client("https://api.themoviedb.org/3/movie/$movie_id?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR&append_to_response=videos");
        //dd($listMovie);

        $form = $this->createForm(TaskType::class, $task);

        return $this->render('movie/index.html.twig', [
            'listMovies' => $movie,
            'promo' => $promo['videos']['results']['0']['key'],
            'form' => $form
        ]);
    }
    #[Route('/show/{movie_id}', name: 'movie_show')]
    public function show(int $movie_id): Response
    {
        $listMovie = $this->client("https://api.themoviedb.org/3/movie/$movie_id?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR&append_to_response=videos");
        //dd($listMovie);
        $movie = new Movie();
        $movie->setVideos($listMovie['videos']['results']['0']['key']);
        $movie->setTitle($listMovie['title']);
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
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
  private string $avis;
  private string $videos;

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
  function getImage(){
    return $this->image;
  }
  function setImage(string $image){
    return $this->image=$image;
  }
  function getVideos(){
    return $this->videos;
  }
  function setVideos(string $videos){
    return $this->videos=$videos;
  }
  function getAvis(){
    return $this->avis;
  }
  function setAvis(string $avis){
    return $this->avis=$avis;
  }
}
