<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Income;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IncomeType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class,[
                'scale' => 2,
            ])
            ->add('frequency', ChoiceType::class,[
                'choices' => [
                    'Quotidienne' => 0,
                    'Hebdommadaire' => 1,
                    'Mensuelle' => 2,
                    'Trimestrielle' => 3,
                    'Annuelle' => 4
                ],
            ]);
            /*
            ->add('budget', EntityType::class, [
                'class' => Budget::class,
                /*'query_builder' => function (EntityRepository $er) use ($id)
                {
                    return $er->createQueryBuilder('u')
                        ->select('u.name')
                        ->where('u.userLink = :currentUser')
                        ->setParameter('currentUser', $id),
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ]) */


        // grab the user, do a quick sanity check that one exists
        $user = $this->security->getUser();
        if (!$user) {
            throw new LogicException(
                'The FriendMessageFormType cannot be used without an authenticated user!'
            );
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user){
            // ... add a choice list of friends of the current application user
            /*
            if (null !== $event->getData()->getFriend()) {
                // we don't need to add the friend field because
                // the message will be addressed to a fixed friend
                return;
            }*/

            $form = $event->getForm();

            $formOptions = [
                'class' => Budget::class,
                'choice_label' => 'name',
                'query_builder' => function (BudgetRepository $br) use ($user)
                {

                    return $query = $br->createQueryBuilder('b')
                        ->join('b.userLink','u')
                        ->where('u.email = :id')
                        ->setParameter('id',$user->getUsername());

                },
            ];


            $form->add('budget', EntityType::class, $formOptions);


        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Income::class,
        ]);
    }
}
