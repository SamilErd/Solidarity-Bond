<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EditUserType extends AbstractType  
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('FirstName')
            ->add('LastName')
            ->add('phoneNum')
            ->add('password', PasswordType::class)
            ->add('street')
            ->add('postalcode')
            ->add('country', ChoiceType::class, [
                'choices'  => [
                    'France' => "France",
                    'Allemagne' => "Allemagne",
                    'Espagne' => "Espagne",
                ],
            ]);
    }
    

   

}
