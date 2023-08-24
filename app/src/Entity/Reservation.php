<?php
/**
 * Reservation entity.
 */

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Reservation.
 *
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @ORM\Table(name="reservations")
 */
class Reservation
{
    /**
     * Primary key.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     type="integer"
     * )
     */
    private int $id;

    /**
     * Return date.
     *
     * @ORM\Column(
     *     type="date"
     * )
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private DateTimeInterface $returnDate;

    /**
     * Rental date.
     *
     * @ORM\Column(
     *     type="date"
     * )
     *
     * @Assert\Type(type="\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private DateTimeInterface $rentalDate;

    /**
     * Element entity.
     *
     * @ORM\ManyToOne(
     *     targetEntity=Element::class,
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private Element $element;

    /**
     * User.
     *
     * @ORM\ManyToOne(
     *     targetEntity=User::class,
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * Comment.
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="255"
     * )
     */
    private string $comment;

    /**
     * Getter for Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for return date.
     *
     * @return \DateTimeInterface|null
     */
    public function getReturnDate(): ?DateTimeInterface
    {
        return $this->returnDate;
    }

    /**
     * Setter for return date.
     *
     * @param \DateTimeInterface $returnDate Return Date
     */
    public function setReturnDate(DateTimeInterface $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    /**
     * Getter for rental date.
     *
     * @return \DateTimeInterface|null
     */
    public function getRentalDate(): ?DateTimeInterface
    {
        return $this->rentalDate;
    }

    /**
     * Setter for rental date.
     *
     * @param \DateTimeInterface $rentalDate rentalDate
     */
    public function setRentalDate(DateTimeInterface $rentalDate): void
    {
        $this->rentalDate = $rentalDate;
    }

    /**
     * Getter for Element.
     *
     * @return Element|null Element
     */
    public function getElement(): ?Element
    {
        return $this->element;
    }

    /**
     * Setter for Element.
     *
     * @param Element $element Element
     */
    public function setElement(Element $element): void
    {
        $this->element = $element;
    }

    /**
     * Getter for User.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for User.
     *
     * @param User $user User
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for comment.
     *
     * @return string|null
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
}
