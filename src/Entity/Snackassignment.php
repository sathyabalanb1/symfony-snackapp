<?php

namespace App\Entity;

use App\Repository\SnackassignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SnackassignmentRepository::class)
 */
class Snackassignment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Snacks::class, inversedBy="snackid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $snack;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="vendorid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendor;

    /**
     * @ORM\Column(type="date")
     */
    private $presentdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedtime;

    /**
     * @ORM\OneToMany(targetEntity=Selection::class, mappedBy="assignment")
     */
    private $assignmentid;

    public function __construct()
    {
        $this->assignmentid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSnack(): ?snacks
    {
        return $this->snack;
    }

    public function setSnack(?snacks $snack): self
    {
        $this->snack = $snack;

        return $this;
    }

    public function getVendor(): ?vendor
    {
        return $this->vendor;
    }

    public function setVendor(?vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getPresentdate(): ?\DateTimeInterface
    {
        return $this->presentdate;
    }

    public function setPresentdate(\DateTimeInterface $presentdate): self
    {
        $this->presentdate = $presentdate;

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
     * @return Collection<int, Selection>
     */
    public function getAssignmentid(): Collection
    {
        return $this->assignmentid;
    }

    public function addAssignmentid(Selection $assignmentid): self
    {
        if (!$this->assignmentid->contains($assignmentid)) {
            $this->assignmentid[] = $assignmentid;
            $assignmentid->setAssignment($this);
        }

        return $this;
    }

    public function removeAssignmentid(Selection $assignmentid): self
    {
        if ($this->assignmentid->removeElement($assignmentid)) {
            // set the owning side to null (unless already changed)
            if ($assignmentid->getAssignment() === $this) {
                $assignmentid->setAssignment(null);
            }
        }

        return $this;
    }
}
