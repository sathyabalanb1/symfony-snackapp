<?php

namespace App\Entity;

use App\Repository\VendorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VendorRepository::class)
 */
class Vendor
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
    private $vendorname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vendorlocation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedtime;

    /**
     * @ORM\OneToMany(targetEntity=Snackassignment::class, mappedBy="vendor")
     */
    private $vendorid;

    public function __construct()
    {
        $this->vendorid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVendorname(): ?string
    {
        return $this->vendorname;
    }

    public function setVendorname(string $vendorname): self
    {
        $this->vendorname = $vendorname;

        return $this;
    }

    public function getVendorlocation(): ?string
    {
        return $this->vendorlocation;
    }

    public function setVendorlocation(string $vendorlocation): self
    {
        $this->vendorlocation = $vendorlocation;

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
    public function getVendorid(): Collection
    {
        return $this->vendorid;
    }

    public function addVendorid(Snackassignment $vendorid): self
    {
        if (!$this->vendorid->contains($vendorid)) {
            $this->vendorid[] = $vendorid;
            $vendorid->setVendor($this);
        }

        return $this;
    }

    public function removeVendorid(Snackassignment $vendorid): self
    {
        if ($this->vendorid->removeElement($vendorid)) {
            // set the owning side to null (unless already changed)
            if ($vendorid->getVendor() === $this) {
                $vendorid->setVendor(null);
            }
        }

        return $this;
    }
}
