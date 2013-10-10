<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('xxx', 'ya_image_loader');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_ya_image_loader';
    }
}
