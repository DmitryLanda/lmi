<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('showDate', 'date', array(
                'label' => 'lmi.school.common.news.show_date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'lmi.school.common.news.date_placeholder'
                )
            ))
            ->add('title', 'text', array(
                'label' => 'lmi.school.common.news.caption',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.news.caption_placeholder'
                )
            ))
            ->add('content', 'textarea', array(
                'label' => 'lmi.school.common.news.content',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.news.content_placeholder'
                )
            ))
            ->add('currentImages', 'collection', array(
                'label' => 'lmi.school.common.news.image',
                'required' => false,
                'type' => 'hidden',
                'allow_add'    => false,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => false
            ))
            ->add('images', 'collection', array(
                'label' => 'lmi.school.common.news.image',
                'required' => true,
                'type' => 'yandex_fotki_album_choices',
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true
            ))
            ->add('author', 'text', array(
                'label' => 'lmi.school.common.news.author',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'lmi.school.common.news.author_placeholder'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Form\Map\NewsMap'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_news_form';
    }
}
