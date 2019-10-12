<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *          "get"={
 *               "normalization_context"={"groups"={"customer_order:read", "customer_order:item:get"}},
 *          },
 *          "put",
 *          "patch"
 *      },
 *     normalizationContext={"groups"={"customer_order:read"}},
 *     denormalizationContext={"groups"={"customer_order:write"}},
 *     attributes={
 *         "pagination_items_per_page"=10
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isFinished"})
 * @ApiFilter(SearchFilter::class, properties={"firstName"})
 * @ApiFilter(RangeFilter::class, properties={"totalPrice"})
 * @ApiFilter(PropertyFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\CustomerOrderRepository")
 */
class CustomerOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"customer_order:read", "customer_order:write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     maxMessage="Describe your first name in 50 chars or less"
     * )
     */
    private $firstName;

    /**
     * @Groups({"cutomer_order:read", "customer_order:write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * The total price of this customer order in cents.
     *
     * @Groups({"customer_order:read", "customer_order:write"})
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $totalPrice;

    /**
     * @Groups("customer_order:read")
     * @ORM\Column(type="boolean")
     */
    private $isFinished = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerOrderLine", mappedBy="customerOrder", cascade={"persist"})
     * @Groups({"customer_order:read", "customer_order:write"})
     * @Assert\Valid()
     */
    private $customerOrderLines;

    public function __construct(string $firstName)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->firstName = $firstName;
        $this->customerOrderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * How long ago in text that this customer order was created.
     *
     * @Groups("cutomer_order:read")
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection|CustomerOrderLine[]
     */
    public function getCustomerOrderLines(): Collection
    {
        return $this->customerOrderLines;
    }

    public function addCustomerOrderLine(CustomerOrderLine $customerOrderLine): self
    {
        if (!$this->customerOrderLines->contains($customerOrderLine)) {
            $this->customerOrderLines[] = $customerOrderLine;
            $customerOrderLine->setCustomerOrder($this);
        }

        return $this;
    }

    public function removeCustomerOrderLine(CustomerOrderLine $customerOrderLine): self
    {
        if ($this->customerOrderLines->contains($customerOrderLine)) {
            $this->customerOrderLines->removeElement($customerOrderLine);
            // set the owning side to null (unless already changed)
            if ($customerOrderLine->getCustomerOrder() === $this) {
                $customerOrderLine->setCustomerOrder(null);
            }
        }

        return $this;
    }
}
