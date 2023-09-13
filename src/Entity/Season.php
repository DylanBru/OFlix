<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"movies"})
     */
    private $number;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"movies"})
     */
    private $episodesNumber;

    /**
     * Le type de l'attribut $movie sera => un objet => une instance de la classe Movie
     * Et grace a cet objet, on va pouvoir utiliser TOUTES les methodes de l'entité (classe) Movie
     * Par ex, admettons qu'on ait une saison qui a pour numero 4, nb_eisodes 12, et bien pour récupérer le nom de la série on va utiliser cet objet $movie pour récupérer le titre par ex comme ca : getMovie()->getTitle()
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getEpisodesNumber(): ?int
    {
        return $this->episodesNumber;
    }

    public function setEpisodesNumber(int $episodesNumber): self
    {
        $this->episodesNumber = $episodesNumber;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
