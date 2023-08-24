<?php

/**
 * Author entity.
 */

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Author.
 *
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\Table(name="authors")
 */
class Author
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
     * Name.
     *
     * @ORM\Column(
     *     type="string",
     *     length=16
     *     )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="16",
     * )
     */
    private string $name;

    /**
     * Surname.
     *
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="32",
     * )
     */
    private string $surname;

    /**
     * Code.
     *
     * @ORM\Column(
     *     type="string",
     *     length=64
     *     )
     *
     * @Gedmo\Slug(fields={"surname"})
     */
    private string $code;

    /**
     * Elements.
     *
     * @ORM\ManyToMany(
     *     targetEntity=Element::class,
     *     mappedBy="authors"
     * )
     */
    private $elements;

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
     * Author constructor.
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for Surname.
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Getter for fullName.
     *
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string $surname Surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Setter for Code.
     *
     * @param string $code Code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Getter for elements.
     *
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    /**
     * Add Element.
     *
     * @param Element $element Element
     */
    public function addElement(Element $element): void
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->addAuthor($this);
        }
    }

    /**
     * Remove Element.
     *
     * @param Element $element Element
     */
    public function removeElement(Element $element): void
    {
        if ($this->elements->removeElement($element)) {
            $element->removeAuthor($this);
        }
    }
}
