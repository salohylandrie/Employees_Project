<?php

namespace App\Entity;

use App\Repository\EmployeAuditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmployeAuditRepository::class)]
class EmployeAudit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_action = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_mise_ajour = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire_ancien = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire_now = null;

   
    #[ORM\Column(length: 255)]
    #[Groups(['getDateInscri:read'])]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAction(): ?string
    {
        return $this->type_action;
    }

    public function setTypeAction(string $type_action): static
    {
        $this->type_action = $type_action;

        return $this;
    }

    public function getDateMiseAjour(): ?\DateTimeInterface
    {
        return $this->date_mise_ajour;
    }

    public function setDateMiseAjour(\DateTimeInterface $date_mise_ajour): static
    {
        $this->date_mise_ajour = $date_mise_ajour;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSalaireAncien(): ?float
    {
        return $this->salaire_ancien;
    }

    public function setSalaireAncien(float $salaire_ancien): static
    {
        $this->salaire_ancien = $salaire_ancien;

        return $this;
    }

    public function getSalaireNow(): ?float
    {
        return $this->salaire_now;
    }

    public function setSalaireNow(?float $salaire_now): static
    {
        $this->salaire_now = $salaire_now;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
