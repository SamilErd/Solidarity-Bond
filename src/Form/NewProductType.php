<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewProductType extends AbstractType  
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ProductName')
            ->add('Description')
            ->add('Price', IntegerType::class)
            ->add('Image', FileType::class)
            ->add('Stock', IntegerType::class)
            ->add('Ajouter', SubmitType::class);
    }
    

   

}
