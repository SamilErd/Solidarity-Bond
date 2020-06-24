<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Contact;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '^[a-zA-Z-]+$',
                        'message' => "votre prénom n'est pas valide."
                ]),
            ]])
            ->add('lastname', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '^[a-zA-Z-]+$',
                        'message' => "votre nom n'est pas valide."
                  ]),
            ]])
            ->add('phone', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(0|\+33 )[1-9]([-. ]?[0-9]{2} ){3}([-. ]?[0-9]{2})$/i',
                        'htmlPattern' => '^(0|\+33 )[1-9]([-. ]?[0-9]{2} ){3}([-. ]?[0-9]{2})$',
                        'message' => "votre numéro de téléphone n'est pas valide."
                  ]),
            ]])
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }


}
