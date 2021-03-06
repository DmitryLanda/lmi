<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Lmi\Bundle\SchoolBundle\Form\Transformer\ImageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeacherType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'lmi.school.common.teacher.name',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.teacher.name_placeholder'
                )
            ))
            ->add('subject', 'lmi_subjects', array(
                'label' => 'lmi.school.common.teacher.subject',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.teacher.subject_placeholder'
                ),
                'required' => false,
            ))
            ->add('room', 'entity', array(
                'label' => 'lmi.school.common.teacher.room',
                'required' => false,
                'class' => 'LmiSchoolBundle:Room',
                'property' => 'number'
            ))
            ->add('birthday', 'date', array(
                'label' => 'lmi.school.common.teacher.birthday',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.date_placeholder'
                ),
                'required' => false,
            ))
            ->add('hideBirthdayYear', 'checkbox', array(
                'label' => 'lmi.school.common.teacher.hide_birthday',
                'required' => false
            ))
            ->add('category', 'lmi_category_choices', array(
                'label' => 'lmi.school.common.teacher.category_title',
            ))
            ->add('stag', 'number', array(
                'label' => 'lmi.school.common.teacher.stag',
                'required' => false,
            ))
            ->add('contacts', 'collection', array(
                'type' => new ContactType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'lmi.school.common.teacher.contacts',
                'required' => false,
            ))
            ->add('email', 'email', array(
                'label' => 'lmi.school.common.teacher.email',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.teacher.email_placeholder'
                )
            ))
            ->add('education', 'lmi_education_choices', array(
                'label' => 'lmi.school.common.teacher.education_title'
            ))
            ->add('biography', 'textarea', array(
                'label' => 'lmi.school.common.teacher.biography',
                'required' => false
            ))
            ->add('photo', 'ya_image', array(
                'label' => 'lmi.school.common.teacher.photo',
                'required' => false,
            ))
            ->add('projects', 'collection', array(
                'type' => new ProjectType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'lmi.school.common.teacher.projects',
                'required' => false,
            ))
            ->add('regards', 'collection', array(
                'type' => new RegardType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'lmi.school.common.teacher.regards',
                'required' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Form\Map\TeacherMap'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_teacher';
    }
}
