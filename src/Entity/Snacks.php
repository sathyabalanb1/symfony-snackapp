<?php

namespace App\Entity;

use App\Repository\SnacksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SnacksRepository::class)
 */
class Snacks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $snackname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedtime;

    /**
     * @ORM\OneToMany(targetEntity=Snackassignment::class, mappedBy="snack")
     */
    private $snackid;

    public function __construct()
    {
        $this->snackid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSnackname(): ?string
    {
        return $this->snackname;
    }

    public function setSnackname(string $snackname): self
    {
        $this->snackname = $snackname;

        return $this;
    }

    public function getCreatedtime(): ?\DateTimeInterface
    {
        return $this->createdtime;
    }

    public function setCreatedtime(?\DateTimeInterface $createdtime): self
    {
        $this->createdtime = $createdtime;

        return $this;
    }

    public function getModifiedtime(): ?\DateTimeInterface
    {
        return $this->modifiedtime;
    }

    public function setModifiedtime(?\DateTimeInterface $modifiedtime): self
    {
        $this->modifiedtime = $modifiedtime;

        return $this;
    }

    /**
     * @return Collection<int, Snackassignment>
     */
    public function getSnackid(): Collection
    {
        return $this->snackid;
    }

    public function addSnackid(Snackassignment $snackid): self
    {
        if (!$this->snackid->contains($snackid)) {
            $this->snackid[] = $snackid;
            $snackid->setSnack($this);
        }

        return $this;
    }

    public function removeSnackid(Snackassignment $snackid): self
    {
        if ($this->snackid->removeElement($snackid)) {
            // set the owning side to null (unless already changed)
            if ($snackid->getSnack() === $this) {
                $snackid->setSnack(null);
            }
        }

        return $this;
    }
}
