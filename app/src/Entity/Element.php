<?php

/**
 * Element entity.
 */

namespace App\Entity;

use App\Repository\ElementRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Element.
 *
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 * @ORM\Table(name="elements")
 */
class Element
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
     *     length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private string $title;

    /**
     * Description.
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private string $description;

    /**
     * Availability.
     *
     * @ORM\Column(
     *     type="smallint"
     * )
     *
     * @Assert\Type(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     */
    private int $availability;

    /**
     * Code.
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private ?string $code;

    /**
     * Category.
     *
     * @ORM\ManyToOne(
     *     targetEntity=Category::class,
     *     inversedBy="elements",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type (type="App\Entity\Category")
     * @Assert\NotNull
     */
    private Category $category;

    /**
     * Tag.
     *
     * @ORM\ManyToMany(
     *     targetEntity=Tag::class,
     *     inversedBy="elements"
     * )
     *
     * @ORM\JoinTable(
     *     name="elements_tags"
     * )
     */
    private $tags;

    /**
     * Authors.
     *
     * @ORM\ManyToMany(
     *     targetEntity=Author::class,
     *     inversedBy="elements"
     * )
     *
     * @ORM\JoinTable (name="elements_authors")
     */
    private $authors;

    /**
     * Created at.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private DateTimeInterface $createdAt;

    /**
     * Element constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @param string $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for Availability.
     *
     * @return int|null Availability
     */
    public function getAvailability(): ?int
    {
        return $this->availability;
    }

    /**
     * Setter for Availability.
     *
     * @param int $availability Availability
     */
    public function setAvailability(int $availability): void
    {
        $this->availability = $availability;
    }

    /**
     * Getter for category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category $category Category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
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
     * Tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag.
     *
     * @param Tag $tag Tag
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Getter for Authors.
     *
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * Add Authors.
     *
     * @param Author $author Author
     */
    public function addAuthor(Author $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }
    }

    /**
     * Remove Author.
     *
     * @param Author $author Author
     */
    public function removeAuthor(Author $author): void
    {
        $this->authors->removeElement($author);
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeInterface $createdAt Created at
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
