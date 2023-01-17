<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 280)]
    #[Assert\Length(min: 2, max: 280)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $likeCount = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'tweet', targetEntity: Photo::class)]
    private Collection $attachments;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'likedTweets')]
    private Collection $linkingUsers;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->linkingUsers = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLikeCount(): ?int
    {
        return $this->likeCount;
    }

    public function setLikeCount(int $likeCount): self
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Photo $photo): self
    {
        if (!$this->attachments->contains($photo)) {
            $this->attachments->add($photo);
            $photo->setTweet($this);
        }

        return $this;
    }

    public function removeAttachment(Photo $photo): self
    {
        if ($this->attachments->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTweet() === $this) {
                $photo->setTweet(null);
            }
        }

        return $this;
    }

    public function addPhoto(Photo $photo): self {
        return $this->addAttachment($photo);
    }

    /**
     * @return Collection<int, User>
     */
    public function getLinkingUsers(): Collection
    {
        return $this->linkingUsers;
    }

    public function addLinkingUser(User $linkingUser): self
    {
        if (!$this->linkingUsers->contains($linkingUser)) {
            $this->linkingUsers->add($linkingUser);
            $linkingUser->addLikedTweet($this);
        }

        return $this;
    }

    public function removeLinkingUser(User $linkingUser): self
    {
        if ($this->linkingUsers->removeElement($linkingUser)) {
            $linkingUser->removeLikedTweet($this);
        }

        return $this;
    }
}
