<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Enrollment;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrollmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'fullName',
                'placeholder' => 'Select Student',
                'query_builder' => function (\App\Repository\StudentRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->orderBy('s.firstName', 'ASC');
                },
                'attr' => ['class' => 'form-select select2']
            ])
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'name',
                'placeholder' => 'Select Course',
                'query_builder' => function (\App\Repository\CourseRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => ['class' => 'form-select select2']
            ])
            ->add('enrolledAt', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('grade', NumberType::class, [
                'required' => false,
                'attr' => ['step' => '0.1', 'min' => 0, 'max' => 10, 'class' => 'form-control']
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => 'active',
                    'Completed' => 'completed',
                    'Dropped' => 'dropped',
                ],
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
        ]);
    }
}
