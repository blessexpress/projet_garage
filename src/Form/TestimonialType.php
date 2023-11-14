<?php

namespace App\Form;

use App\Entity\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("fullname", TextType::class,
                [
                    "label" => "Votre Nom",
                    "required" => true
                ])
            ->add("note", ChoiceType::class, ['choices' => [
                '5/5 (Satisfait)' => '5',
                '4/5 (Bien)' => '4',
                '3/5 (Moyen)' => '3',
                '2/5 (Peu satisfait)' => '2',
                '1/5 (Pas du tout satisfait)' => '1',
            ], "label" => "Qu'avez-vous pensé de nos services ?", "required" => true])
            ->add('comment', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => 'Message',
                'required' => true,
            ]);

        // Ajoutez le champ "approved" pour les administrateurs
        if ($options['is_admin']) {
            $builder->add("approved", ChoiceType::class, [
                "label" => "Approuver le témoignage ?",
                "required" => true,
                "choices" => [
                    "Oui" => true,
                    "Non" => false,
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
            'is_admin' => false,
        ]);
    }
}
