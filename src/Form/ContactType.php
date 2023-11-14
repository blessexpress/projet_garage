<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', null, [
                'label' => 'Objet ',
                'required' => true,
            ])
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('first_name', null, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Adresse e-mail',
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => 'Message',
                'required' => true,
            ])
            ->add('phone', null, [
                'label' => 'Numéro de téléphone',
                'required' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
