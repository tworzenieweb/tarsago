<?php

namespace Targeo\ExportBundle\Form;

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
                ->add('file', null, array(
                    'label' => 'Plik wejściowy'
                ))
                ->add('delimeter', null, array(
                    'label' => 'Znak oddzielający (separator)'
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
            'data_class' => 'Targeo\ExportBundle\Entity\Export'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'targeo_exportbundle_export';
    }

}
