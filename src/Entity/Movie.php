<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_USER')"},
 *          "update_poster"={
 *              "method"="GET",
 *              "controller"="\App\Controller\Movie\UpdatePosterController",
 *              "path"="/movie/update-poster"
 *           }
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_USER')"},
 *          "delete"={"security"="is_granted('ROLE_USER')"},
 *          "patch"={"security"="is_granted('ROLE_USER')"},
 *     }
 * )
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity
 */
class Movie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    public $title;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    public $duration;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="People", inversedBy="movie")
     * @ORM\JoinTable(name="movie_has_people",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Movie_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="People_id", referencedColumnName="id")
     *   }
     * )
     */
    public $people;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Type", inversedBy="movie")
     * @ORM\JoinTable(name="movie_has_type",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Movie_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Type_id", referencedColumnName="id")
     *   }
     * )
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    public $image;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->type = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return Collection
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param Collection $people
     */
    public function setPeople($people): void
    {
        $this->people = $people;
    }

    /**
     * @return Collection
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Collection $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

}
