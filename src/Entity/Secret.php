<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use SpecShaper\EncryptBundle\Annotations\Encrypted;

/**
 * @ORM\Entity(repositoryClass=SecretRepository::class)
 */
class Secret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=700)
     * @Assert\NotBlank(message="Secret cannot be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Secret must be at least {{ limit }} characters long",
     *      maxMessage = "Secret cannot be longer than {{ limit }} characters"
     * )
     * @var string
     * @Encrypted
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=700)
     * @Assert\NotBlank(message="Passphrase cannot be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "Passphrase must be at least {{ limit }} characters long",
     *      maxMessage = "Passphrase cannot be longer than {{ limit }} characters"
     * )
     * @var string
     * @Encrypted
     */
    private $passphrase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPassphrase(): ?string
    {
        return $this->passphrase;
    }

    public function setPassphrase(string $passphrase): self
    {
        $this->passphrase = $passphrase;

        return $this;
    }
}
