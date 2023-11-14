<?php

namespace App\Form;

use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                "constraints" => [
                    new Length(["min" => 1, "max" => 255, "minMessage" => "Le titre de l\'équipement ne peux pas être inférieur à 1 caratères", "maxMessage" => "Le titre de l\'équipement ne peux pas être supérieur à 255 caratères"]),
                    new NotBlank(["message" => "Le nom de l\'équipement ne peut pas être vide"])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
