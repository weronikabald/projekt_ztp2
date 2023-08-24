<?php
/**
 * Users Data entity.
 */

namespace App\Entity;

use App\Repository\UsersDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersDataRepository::class)
 * @ORM\Table (
 *     name="users_data"
 * )
 */
class UsersData
{
    /**
     * Id.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     type="integer"
     * )
     */
    private int $id;

    /**
     * First name.
     *
     * @ORM\Column(
     *     type="string",
     *     length=16
     * )
     *
     * @Assert\Length (
     *     min="3",
     *     max="16"
     * )
     */
    private string $firstName;

    /**
     * Last name.
     *
     * @ORM\Column(
     *     type="string",
     *     length=32
     * )
     *
     * @Assert\Length (
     *     min="3",
     *     max="32"
     * )
     */
    private string $lastName;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for first name.
     *
     * @return string|null first name
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Setter for first name.
     *
     * @param string $firstName first name
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Getter for last name.
     *
     * @return string|null last name
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Setter for last name.
     *
     * @param string $lastName last name
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
