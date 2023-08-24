<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"title"})
 */
class Tag
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
     * Title.
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
     *     max="16"
     * )
     */
    private string $title;

    /**
     * Code.
     *
     * @ORM\Column(
     *     type="string",
     *     length=16
     *     )
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="16"
     * )
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private string $code;

    /**
     * Elements.
     *
     * @ORM\ManyToMany(
     *     targetEntity=Element::class,
     *     mappedBy="tags"
     * )
     */
    private $elements;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

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
     * Getter for title.
     *
     * @return string|null
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
     * Getter for code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Setter for code.
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
     * @param \App\Entity\Element $element Element
     */
    public function addElement(Element $element): void
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->addTag($this);
        }
    }

    /**
     * Remove element.
     *
     * @param \App\Entity\Element $element Element
     */
    public function removeElement(Element $element): void
    {
        if ($this->elements->removeElement($element)) {
            $element->removeTag($this);
        }
    }
}
