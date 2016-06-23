<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $accountToOptions = [
            'label' => 'Recipient account',
            'class' => 'AppBundle:AccountEntity',
        ];

        if (isset($options['accountsTo'])) {
            $accountToOptions['choices'] = $options['accountsTo'];
        }

        $builder
            ->add('accountTo', EntityType::class, $accountToOptions)
            ->add('amount', IntegerType::class)
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'transaction';
    }

    /**
     * @param OptionsResolver $resolver
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Model\Dto\Transaction',
            'accountsTo' => null,
        ]);
    }
}
