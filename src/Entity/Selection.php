<?php

namespace App\Entity;

use App\Repository\SelectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SelectionRepository::class)
 */
class Selection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="userid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Snackassignment::class, inversedBy="assignmentid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assignment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedtime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isselected;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?users
    {
        return $this->user;
    }

    public function setUser(?users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAssignment(): ?snackassignment
    {
        return $this->assignment;
    }

    public function setAssignment(?snackassignment $assignment): self
    {
        $this->assignment = $assignment;

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

    public function getIsselected(): ?bool
    {
        return $this->isselected;
    }

    public function setIsselected(bool $isselected): self
    {
        $this->isselected = $isselected;

        return $this;
    }
}
