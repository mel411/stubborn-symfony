<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'empty_data' => '1.jpeg',
            ])
            ->add('isFeatured', CheckboxType::class, [
                'label' => 'Produit en vedette',
                'required' => false,
            ])
            ->add('stockXS', IntegerType::class, [
                'label' => 'Stock XS',
                'mapped' => false,
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stockS', IntegerType::class, [
                'label' => 'Stock S',
                'mapped' => false,
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stockM', IntegerType::class, [
                'label' => 'Stock M',
                'mapped' => false,
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stockL', IntegerType::class, [
                'label' => 'Stock L',
                'mapped' => false,
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stockXL', IntegerType::class, [
                'label' => 'Stock XL',
                'mapped' => false,
                'required' => false,
                'empty_data' => '0',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}