<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SitePageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('content')
            ->add('title')
            ->add('description')
            ->add('keywords')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Entity\SitePage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_bundle_schoolbundle_sitepage';
    }
}
