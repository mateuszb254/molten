<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketAnswerRepository")
 */
class TicketAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ticket", inversedBy="answers")
     */
    private $ticket;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     */
    private $isAdminAnswer = false;

    public function getId()
    {
        return $this->id;
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

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getIsAdminAnswer(): ?bool
    {
        return $this->isAdminAnswer;
    }

    public function setIsAdminAnswer(string $isAdminAnswer): self
    {
        $this->isAdminAnswer = $isAdminAnswer;

        return $this;
    }
}
