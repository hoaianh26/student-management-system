<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_enrollment', fields: ['student', 'course'])]
#[UniqueEntity(
    fields: ['student', 'course'],
    message: 'This student is already enrolled in this course.'
)]
class Enrollment
{
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DROPPED   = 'dropped';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Please select a student.')]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Please select a course.')]
    private ?Course $course = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private ?\DateTimeInterface $enrolledAt = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Range(min: 0, max: 10, notInRangeMessage: 'Grade must be between {{ min }} and {{ max }}.')]
    private ?float $grade = null;

    #[ORM\Column(length: 20, options: ['default' => 'active'])]
    #[Assert\Choice(choices: [self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_DROPPED])]
    private string $status = self::STATUS_ACTIVE;

    public function __construct()
    {
        $this->enrolledAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getStudent(): ?Student { return $this->student; }
    public function setStudent(?Student $student): static { $this->student = $student; return $this; }

    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(?Course $course): static { $this->course = $course; return $this; }

    public function getEnrolledAt(): ?\DateTimeInterface { return $this->enrolledAt; }
    public function setEnrolledAt(\DateTimeInterface $enrolledAt): static { $this->enrolledAt = $enrolledAt; return $this; }

    public function getGrade(): ?float { return $this->grade; }
    public function setGrade(?float $grade): static { $this->grade = $grade; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
}