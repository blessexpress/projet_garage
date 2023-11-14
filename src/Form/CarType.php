<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "Titre", "required" => true,
                "constraints" => [
                    new Length(["min" => 1, "max" => 200, "minMessage" => "Le titre ne peux pas être inférieur à 1 caratères", "maxMessage" => "Le titre ne peux pas être supérieur à 60 caratères"]),
                    new NotBlank(["message" => "Le titre ne peut pas être vide"])
                ]
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description", "required" => true,
                "constraints" => [
                    new Length(["min" => 1,"minMessage" => "La description ne peux pas être inférieur à 1 caratères"]),
                    new NotBlank(["message" => "La description ne peut pas être vide"])
                ]
            ])
            ->add("circulation_year", NumberType::class, ["label" => "Année de Mise en Circulation", "required" => true,  'attr' => ['maxlength' => 4]])
            ->add('mileage', NumberType::class, [
                'required' => true,
                'label' => 'Kilométrage'
            ])
            ->add("price", NumberType::class, ["label" => "Prix", "required" => true])
            ->add('imageFile', VichFileType::class, [
                'required' => true,
                "label" => "Image principale",
            ])
            ->add('galleries', FileType::class, [
                'label' => 'Ajouter des images à la galerie',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('features', CollectionType::class, [
                'entry_type' => FeatureType::class,
                'entry_options' => ['label' => 'Caractéristique'],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => OptionType::class,
                'entry_options' => ['label' => 'Options'],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('equipment', CollectionType::class, [
                'entry_type' => EquipmentType::class,
                'entry_options' => ['label' => 'Equipements'],
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
