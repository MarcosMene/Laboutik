<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom à votre adresse',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre societé - (facultatif)',
                'required'=>false,
                'attr' => [
                    'placeholder' => 'Societé'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse ?',
                'attr' => [
                    'placeholder' => '8 rue de lylas...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => '777777'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('country', CountryType::class, [ //countrytype pour country
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'Pays',
                    'class' => 'w-100'
                ]
            ])
            ->add('phone', TelType::class, [ //teltype pour telephone
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}