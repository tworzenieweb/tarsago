<?php

namespace Tarsago\ExportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExportType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('filename', null, array(
                    'label' => 'Nazwa pliku wyjściowego'
                ))
                ->add('file', null, array(
                    'label' => 'Plik wejściowy'
                ))
                ->add('createdAt', 'text', array(
                    'label' => 'Data exportu',
                    'attr' => array(
                        'widget_col' => '2', 
                        'class' => 'date',
                        'input_group' => array('append' => '.icon-th')
                    )
                ))
                ->add('delimeter', null, array(
                    'label' => 'Znak oddzielający (separator)',
                    'attr' => array('widget_col' => '1')
                ))
                ->add('actions', 'form_actions', array(
                    'buttons' => array(
                        'save' => array('type' => 'submit', 'options' => array('label' => 'Prześlij', 'attr' => array('class' => 'pull-right'))),
                    )
                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tarsago\ExportBundle\Entity\Export'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tarsago_exportbundle_export';
    }

}
