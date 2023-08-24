<?php

/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 */
class Category
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
     * Title.
     *
     * @ORM\Column(
     *     type="string",
     *     length=16)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="16",
     * )
     */
    private string $title;

    /**
     * Collection of elements.
     *
     * @ORM\OneToMany(
     *     targetEntity=Element::class,
     *     mappedBy="category",
     *     fetch="EXTRA_LAZY"
     * )
     */
    private Collection $elements;

    /**
     * Code.
     *
     * @ORM\Column(
     *     type="string",
     *     length=64
     *     )
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private string $code;

    /**
     * Getter for Id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Elements.
     *
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    /**
     * Add element to collection if it's not there.
     *
     * @param \App\Entity\Element $element
     */
    public function addElement(Element $element): void
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->setCategory($this);
        }
    }

    /**
     * Delete element if it's on the list.
     *
     * @param \App\Entity\Element $element
     */
    public function removeElement(Element $element): void
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getCategory() === $this) {
                $element->setCategory(null);
            }
        }
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
}
