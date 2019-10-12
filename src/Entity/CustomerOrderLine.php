<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ApiResource(
 *     normalizationContext={"group"={"customer_order_line:read"}},
 *     denormalizationContext={"group"={"customer_order_line:write"}}
 * )
 * @ApiFilter(PropertyFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\CustomerOrderLineRepository")
 */
class CustomerOrderLine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"customer_order_line:read", "customer_order_line:write", "customer_order:item:get", "customer_order:write"})
     * @Assert\NotBlank()
     */
    private $articleId;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"customer_order_line:read", "customer_order_line:write", "customer_order:item:get", "customer_order:write"})
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"customer_order_line:read", "customer_order_line:write", "customer_order:item:get", "customer_order:write"})
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerOrder", inversedBy="customerOrderLines")
     */
    private $customerOrder;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCustomerOrder(): ?CustomerOrder
    {
        return $this->customerOrder;
    }

    public function setCustomerOrder(?CustomerOrder $customerOrder): self
    {
        $this->customerOrder = $customerOrder;

        return $this;
    }
}
