<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class RegisterType extends AbstractType  
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('FirstName', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '^[a-zA-Z-]+$',
                        'message' => "Votre prénom n'est pas valide."
                  ]),
            ]])
            ->add('LastName', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '^[a-zA-Z-]+$',
                        'message' => "Votre nom n'est pas valide."
                  ]),
            ]])
            ->add('phoneNum', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(0|\+33 )[1-9]([-. ]?[0-9]{2} ){3}([-. ]?[0-9]{2})$/i',
                        'htmlPattern' => '^(0|\+33 )[1-9]([-. ]?[0-9]{2} ){3}([-. ]?[0-9]{2})$',
                        'message' => "Votre numéro de téléphone n'est pas valide."
                  ]),
            ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'confirm'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/',
                        'htmlPattern' => '^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}$',
                        'message' => "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, et 1 chiffre."
                  ]),
                        
            ]])             
            ->add('street')
            ->add('postalcode', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[0-9]{5,5}$/i',
                        'htmlPattern' => '^[0-9]{5,5}$',
                        'message' => "Votre code postal n'est pas valide."
                  ]),
            ]])
            ->add('country', ChoiceType::class, [
                'choices'  => [
                    'France' => "France",
                    'Allemagne' => "Allemagne",
                    'Espagne' => "Espagne",
                ],
            ]);
    }
    

   

}
