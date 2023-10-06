<?php

namespace App\Entity;

use App\Repository\SondageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SondageRepository::class)]
class Sondage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private $description;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\OneToOne(targetEntity: Question::class, mappedBy: 'sondage', cascade: ['persist', 'remove'])]
    private ?Question $question = null;

    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $answers;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'sondage_votants')]
    private Collection $votants;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $actif = null;



    public function __construct()
    {
        $this->votants = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {

        $this->question = $question;

        return $this;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function setAnswers(Answer $answers): self
    {
        if (!$this->answers->contains($answers)) {
            $this->answers[] = $answers;
            $answers->setSondage($this);
        }
        return $this;
    }

    public function addVotant(User $user): void
    {
        if(!$this->votants->contains($user))
        {
            $this->votants->add($user);
        }
    }

    public function isVotant(User $user)
    {
        return $this->votants->contains($user);
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

}
