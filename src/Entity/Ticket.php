<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    public const STATUS_OPEN = 0;
    public const STATUS_ANSWERED = 1;
    public const STATUS_CLOSED = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=30)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = self::STATUS_OPEN;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TicketAnswer", mappedBy="ticket", cascade={"persist"})
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TicketCategory", inversedBy="tickets")
     * @Assert\NotBlank(message="ticket.category.select")
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Image()
     */
    private $attachment;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Account
    {
        return $this->author;
    }

    public function setAuthor(Account $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function addAnswer(TicketAnswer $answer): self
    {
        $answer->setTicket($this);

        $this->answers[] = $answer;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function setCategory(TicketCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): ?TicketCategory
    {
        return $this->category;
    }
}
