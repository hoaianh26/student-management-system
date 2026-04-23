<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;

class BulkEnrollmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => function(Course $course) {
                    return $course->getCode() . ' - ' . $course->getName();
                },
                'placeholder' => '--- Select a Course ---',
                'query_builder' => function (\App\Repository\CourseRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Please select a course.']),
                ],
                'attr' => ['class' => 'form-select select2'],
            ])
            ->add('students', EntityType::class, [
                'class' => Student::class,
                'choice_label' => function(Student $student) {
                    return $student->getFirstName() . ' ' . $student->getLastName() . ' (' . $student->getEmail() . ')';
                },
                'query_builder' => function (\App\Repository\StudentRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->orderBy('s.firstName', 'ASC');
                },
                'multiple' => true,
                'expanded' => true, // This renders as checkboxes
                'constraints' => [
                    new Count(['min' => 1, 'minMessage' => 'Please select at least one student.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // No data_class because this form doesn't represent a single entity
        ]);
    }
}
