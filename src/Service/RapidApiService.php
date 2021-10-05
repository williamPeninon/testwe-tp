<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class RapidApiService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->environment = $kernel->getEnvironment();
        $this->em = $em;
    }

    /**
     * @param array $movieList
     * @return array
     * @throws Exception
     */
    public function getMoviesCover(array $movieList)
    {
        // Fixture:Exceeded the MONTHLY quota for Requests on your current plan, BASIC.
        $response = '{"d": [{"i": {"height": 2005,"imageUrl": "https://m.media-amazon.com/images/M/MV5BMTU0Nzc5NzI5NV5BMl5BanBnXkFtZTgwNTk1MDE4MDI@._V1_.jpg","width": 1600},"id": "tt1935859","l": "Miss Peregrine\'s Home for Peculiar Children","q": "feature","rank": 1106,"s": "Eva Green, Asa Butterfield","v": [{"i": {"height": 720,"imageUrl": "https://m.media-amazon.com/images/M/MV5BMTUyNjY3ODQ3Nl5BMl5BanBnXkFtZTgwNDMxNTIyOTE@._V1_.jpg","width": 1280},"id": "vi3400709913","l": "Trailer #2","s": "2:22"}, {"i": {"height": 360,"imageUrl": "https://m.media-amazon.com/images/M/MV5BNjE4NDkzYWMtZTE3MC00ZDBiLWE5YjUtZmYxMmRiOGNlY2VmXkEyXkFqcGdeQXVyNzU1NzE3NTg@._V1_.jpg","width": 480},"id": "vi4058953241","l": "Miss Peregrine\'s Home for Peculiar Children","s": "0:36"}, {"i": {"height": 360,"imageUrl": "https://m.media-amazon.com/images/M/MV5BNmQwZDQ2NDQtYWRlMi00ZTcyLTllYjEtMTJhZjBkNTQ5ZjIyXkEyXkFqcGdeQXVyNzU1NzE3NTg@._V1_.jpg","width": 480},"id": "vi4291999769","l": "Miss Peregrine\'s Home for Peculiar Children","s": "1:36"}],"vt": 12,"y": 2016}],"q": "miss peregrine et les enfants particuliers","v": 1}';

        $repo = $this->em->getRepository(Movie::class);

        $curl = curl_init();
        foreach ($movieList as $movie) {
            $encodedMovieTile = urlencode($movie['title']);
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://imdb8.p.rapidapi.com/auto-complete?q={$encodedMovieTile}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: {$this->container->getParameter('x-rapidapi-host')}",
                    "x-rapidapi-key: {$this->container->getParameter('x-rapidapi-key')}"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if ($err && $this->environment === 'dev') {
                throw new Exception('cURL Error #:' . $err);
            } elseif (curl_getinfo($curl)['http_code'] === 200) {
                $res = json_decode($response);
                if (isset($res->d) && count($res->d) > 0) {
                    if (isset($res->d[0]->i)) {
                        $aResult = $repo->findOneBy(['id' => $movie['id']]);
                        $aResult->setImage($res->d[0]->i->imageUrl);
                        $this->em->flush();
                    }
                }
            }
        }
        curl_close($curl);

        return ['status' => 'success'];
    }
}
