<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AccountEntityType
 * @author mfris
 * @package AppBundle\Form
 */
class AccountEntityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $queryBuilder = function (EntityRepository $er) {
            return $er->createQueryBuilder('u')
                ->join('u.role', 'r')
                ->where("r.name = 'ROLE_USER'")
                ->orderBy('u.username', 'ASC');
        };

        $builder
            ->add('owner', EntityType::class, [
                'class' => 'AppBundle:UserEntity',
                'choice_label' => 'name',
                'query_builder' => $queryBuilder,
            ])
            ->add('disponents', EntityType::class, [
                'class' => 'AppBundle:UserEntity',
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'query_builder' => $queryBuilder,
            ])
            ->add('balance')
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'account';
    }

    /**
     * @param OptionsResolver $resolver
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\Entity\AccountEntity'
        ));
    }
}
