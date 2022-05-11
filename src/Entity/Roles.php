<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolesRepository::class)
 */
class Roles
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
    private $rolename;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedtime;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="role")
     */
    private $roleid;

    public function __construct()
    {
        $this->roleid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolename(): ?string
    {
        return $this->rolename;
    }

    public function setRolename(string $rolename): self
    {
        $this->rolename = $rolename;

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
     * @return Collection<int, Users>
     */
    public function getRoleid(): Collection
    {
        return $this->roleid;
    }

    public function addRoleid(Users $roleid): self
    {
        if (!$this->roleid->contains($roleid)) {
            $this->roleid[] = $roleid;
            $roleid->setRole($this);
        }

        return $this;
    }

    public function removeRoleid(Users $roleid): self
    {
        if ($this->roleid->removeElement($roleid)) {
            // set the owning side to null (unless already changed)
            if ($roleid->getRole() === $this) {
                $roleid->setRole(null);
            }
        }

        return $this;
    }
}
