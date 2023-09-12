<?php
/**
 * Reservation entity.
 */

namespace App\Entity;

use App\Entity\Enum\ReservationStatusEnum;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Reservation.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservations')]
class Reservation
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Comment.
     */
    #[ORM\Column(type: 'text')]
    private ?string $comment = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    private ?string $email = null;

    /**
     * Nickname.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    private ?string $nickname = null;

    /**
     * Created at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Element.
     */
    #[ORM\ManyToOne(targetEntity: Element::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $element = null;

    /**
     * Status of reservation.
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $status = null;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for comment.
     *
     * @return string|null Comment
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Setter for comment.
     *
     * @param string $comment Comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for nickname.
     *
     * @return string|null Nickname
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Setter for nickname.
     *
     * @param string $nickname Nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * Getter for createdAt.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for createdAt.
     *
     * @param \DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updatedAt.
     *
     * @return \DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     *
     * @param \DateTimeImmutable|null $updatedAt Updated at
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for element.
     *
     * @return Element|null Element
     */
    public function getElement(): ?Element
    {
        return $this->element;
    }

    /**
     * Setter for element.
     *
     * @param Element|null $element Element
     */
    public function setElement(?Element $element): void
    {
        $this->element = $element;
    }

    /**
     * Getter for status.
     *
     * @return string|null Status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Setter for status.
     *
     * @param string|null $status Status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
