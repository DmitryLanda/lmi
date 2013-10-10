<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'lmi.school.common.project.name',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.project.name_placeholder'
                ))
            )
            ->add('document', 'url', array(
                'label' => 'lmi.school.common.project.document',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.project.document_placeholder'
                ))
            )
            ->add('publishedAt', 'date', array(
                'label' => 'lmi.school.common.project.published_at',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.date_placeholder'
                ))
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_project';
    }
}
