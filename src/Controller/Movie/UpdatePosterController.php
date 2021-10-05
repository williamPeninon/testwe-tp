<?php declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\Movie;
use App\Service\RapidApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdatePosterController extends AbstractController
{
    /**
     * @var RapidApiService
     */
    public $rapidApiService;

    /**
     * @required
     * @param    RapidApiService $rapidApiService
     */
    public function setRapidApiService(RapidApiService $rapidApiService): void
    {
        $this->rapidApiService = $rapidApiService;
    }

    /**
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(EntityManagerInterface $em): JsonResponse
    {
        $aMoviesWithoutPoster = $em->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->select('m.id, m.title, m.image')
            ->where('m.image = \'\'')
            ->getQuery()
            ->getArrayResult();

        $result = $this->rapidApiService->getMoviesCover($aMoviesWithoutPoster);

        return new JsonResponse($result);
    }
}
