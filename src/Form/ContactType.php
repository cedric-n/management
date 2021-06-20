<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class,[
                'label' => 'Sujet',
                'required' => true,
            ])
            ->add('name', TextType::class,[
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', EmailType::class,[
                'label' => 'E-mail',
                'required' => true,
            ])
            ->add('message', TextareaType::class,[
                'label' => 'Message',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
