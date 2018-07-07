<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @UniqueEntity(fields="login", message="account.login.unique")
 * @UniqueEntity(fields="email", message="account.email.unique")
 */
class Account implements UserInterface
{
    const DEFAULT_ROLE = 'ROLE_USER';
    const ADMIN_ROLE = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="default.not_blank")
     * @Assert\Length(min=6, max=24, minMessage="account.login.minLength", maxMessage="account.login.maxLength")
     */
    private $login;

    /**
     * @Assert\NotBlank(message="default.not_blank")
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="default.not_blank")
     * @Assert\Email(message="default.email")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="default.not_blank")
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="default.not_blank")
     */
    private $question;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="default.not_blank")
     */
    private $answer;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $coins = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLog", mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"createdAt": "DESC"})
     */
    private $logs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastActivity;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $banTime;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $banReason;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $roles = [];

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->registeredAt = new \DateTime();
        $this->lastActivity = new \DateTime();
        $this->setRole(self::DEFAULT_ROLE);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin($login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion($question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer($answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCoins(): int
    {
        return $this->coins;
    }

    public function setCoins($coins): self
    {
        $this->coins = $coins;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function setRole(string $role)
    {
        if(!array_search($role, $this->getRoles())) {
            $this->roles[] = $role;
        }
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $string): bool
    {
        return in_array($string, $this->getRoles());
    }

    public function getLogs(): ?Collection
    {
        return $this->logs;
    }

    public function __toString(): string
    {
        return $this->getLogin();
    }

    public function addLog(UserLog $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setUser($this);
        }

        return $this;
    }

    public function setLastActivity(\DateTime $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    public function getLastActivity(): ?\DateTime
    {
        return $this->lastActivity;
    }

    public function getRegisteredAt(): ?\DateTime
    {
        return $this->registeredAt;
    }

    public function setBanTime(\DateTime $banTime)
    {
        $this->banTime = $banTime;
    }

    public function getBanTime(): ?\DateTime
    {
        return $this->banTime;
    }

    public function setBanReason(string $banReason)
    {
        $this->banReason = $banReason;
    }

    public function getBanReason(): ?string
    {
        return $this->banReason;
    }

    public function isBanned(): bool
    {
        return $this->getBanTime() > new \DateTime();
    }

    public function grantCoins(int $amount): self {
        $this->coins += $amount;

        return $this;
    }
}
