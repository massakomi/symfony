<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $user_id;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="integer")
     */
    private int $payment_id = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shipment_id = 0;

    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int|User $user): static
    {
        if (is_numeric($user)) {
            $this->user_id = $user;
        } else {
            $this->user_id = $user->getId();
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string|OrderStatus $status): static
    {
        if ($status instanceof OrderStatus) {
            $this->status = $status->getName();
        } else {
            $this->status = $status;
        }


        return $this;
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function setPaymentId(int|Payment $payment): static
    {
        if (is_numeric($payment)) {
            $this->payment_id = $payment;
        } else {
            $this->payment_id = $payment->getId();
        }

        return $this;
    }

    public function getShipmentId(): ?int
    {
        return $this->shipment_id;
    }

    public function setShipmentId(int|Shipment $shipment): static
    {
        if (is_numeric($shipment)) {
            $this->shipment_id = $shipment;
        } else {
            $this->shipment_id = $shipment->getId();
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): static
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeInterface $update_at): static
    {
        $this->update_at = $update_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->setCreateAt(new \DateTime);
    }

    /**
     * @ORM\PrePersist
     */
    public function setUpdateAtValue(): void
    {
        $this->setUpdateAt(new \DateTime);
    }
}
