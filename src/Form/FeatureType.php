<?php

namespace App\Form;

use App\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom caractéristique',
                "constraints" => [
                    new Length(["min" => 1, "max" => 255, "minMessage" => "Le titre de la caractéristique ne peux pas être inférieur à 1 caratères", "maxMessage" => "Le titre ne peux pas être supérieur à 255 caratères"]),
                    new NotBlank(["message" => "Le titre de la caractéristique ne peut pas être vide"])
                ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        ]);
    }
}
