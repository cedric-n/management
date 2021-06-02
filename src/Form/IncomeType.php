<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Income;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IncomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('frequency', ChoiceType::class,[
                'choices' => [
                    'Quotidienne' => 0,
                    'Hebdommadaire' => 1,
                    'Mensuelle' => 2,
                    'Trimestrielle' => 3,
                    'Annuelle' => 4
                ],
            ])
            ->add('budget', EntityType::class, [
                'class' => Budget::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Income::class,
        ]);
    }
}
