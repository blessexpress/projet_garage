<?php

namespace App\Form;

use App\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];

        foreach ($days as $dayKey => $dayLabel) {
            $builder
                ->add("{$dayKey}AM", TimeType::class, ['widget' => 'single_text', "label" => "Ouverture {$dayLabel} Matin", "required" => true])
                ->add("close{$dayKey}AM", TimeType::class, ['widget' => 'single_text', "label" => "Jusqu'à", "required" => true])
                ->add("{$dayKey}PM", TimeType::class, ['widget' => 'single_text', "label" => "Ouverture {$dayLabel} Après Midi", "required" => true])
                ->add("close{$dayKey}PM", TimeType::class, ['widget' => 'single_text', "label" => " Jusqu'à", "required" => true]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
