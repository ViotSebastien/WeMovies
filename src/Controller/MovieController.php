<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\GenreType;
use App\Form\SearchKeywordType;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    #[Route('/', name: 'movie')]
    public function index(Request $request): Response
    {
        $listMovie = $this->client('https://api.themoviedb.org/3/movie/popular?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR');
        $movies=$this->createlist($listMovie);
        $movie_id = $movies['0']->getId();
        $promo=$this->client("https://api.themoviedb.org/3/movie/$movie_id?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR&append_to_response=videos");
        $test=$this->client('https://api.themoviedb.org/3/genre/movie/list?api_key=d2c58004100fdb2c3d65ba0058594263&language=fr-FR');

        $form = $this->createForm(GenreType::class);
        $search = $this->createForm(SearchKeywordType::class);
        $form->handleRequest($request);
        $search->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $listMovie=null;
          $movies=null;
          $genre = $form->getData();
          $listMovie = $this->client('https://api.themoviedb.org/3/movie/popular?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR');
          $movies=$this->createlist($listMovie);
          if ($genre['Action'] === true) {
            $movies=$this->createListGenre($movies, 28);
          }elseif ($genre['Aventure'] === true) {
            $movies=$this->createListGenre($movies, 12);
          }
        }

        if ($search->isSubmitted() && $search->isValid()) {
          $search = $search->getData();
          dd($search);
        }
        return $this->render('movie/index.html.twig', [
            'listMovies' => $movies,
            'promo' => $promo['videos']['results']['0']['key'],
            'form' => $form->createView(),
            'search' => $search->createView(),
        ]);
    }
    #[Route('/show/{movie_id}', name: 'movie_show')]
    public function show(int $movie_id): Response
    {
        $listMovie = $this->client("https://api.themoviedb.org/3/movie/$movie_id?api_key=d2c58004100fdb2c3d65ba0058594263&region=FR&append_to_response=videos");
        $movieShow = new Movie();
        $movieShow->setVideos($listMovie['videos']['results']['0']['key']);
        $movieShow->setTitle($listMovie['title']);
        return $this->render('movie/show.html.twig', [
            'movie' => $movieShow,
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

    public function createlist(array $listMovie){
      $movie= null;
      foreach ($listMovie['results'] as $key => $value) {
        $movie[] = new Movie();
        $movie[$key]->setId($value["id"]);
        $movie[$key]->setTitle($value["title"]);
        $movie[$key]->setDescription($value["overview"]);
        $movie[$key]->setImage($value['backdrop_path']);
        $movie[$key]->setAvis($value['vote_average']);
        $movie[$key]->setGenre($value['genre_ids']);
      }
      return $movie;
    }

    public function createListGenre(array $movies,int $genre_id){
      $movieGenre= null;
      $i=0;
      foreach ($movies as $key => $value) {
        foreach ($value->getGenre() as $key1 => $value1) {
          if ($value1 === $genre_id) {
            $movieGenre[] = new Movie();
            $movieGenre[$i]->setId($value->getId());
            $movieGenre[$i]->setTitle($value->getTitle());
            $movieGenre[$i]->setDescription($value->getDescription());
            $movieGenre[$i]->setImage($value->getImage());
            $movieGenre[$i]->setAvis($value->getAvis());
            $movieGenre[$i]->setGenre($value->getGenre());
            $i++;
          }else {
            //unset($movieGenre[$key]);
          }
        }
      }
      return $movieGenre;
    }
}

/**
 *
 */
class Movie
{
  private ?string $id = null;
  private ?string $title = null;
  private ?string $description = null;
  private ?string $image = null;
  private ?string $avis = null;
  private ?string $videos = null;

  private array $genre;

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
  function getGenre(){
    return $this->genre;
  }
  function setGenre(array $genre){
    return $this->genre=$genre;
  }
}
