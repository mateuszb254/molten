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

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function getCoins()
    {
        return $this->coins;
    }

    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        // TODO::IMPLEMENT ROLES IN SYSTEM
        return [
            'ROLE_USER', 'ROLE_ADMIN'
        ];
    }

    public function hasRole(string $string)
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
}
