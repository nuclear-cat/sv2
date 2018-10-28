<?php

namespace HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateCome = $options['date_come'];
        $dateLeft = $options['date_left'];
        
        $dateComeFormatted = $dateCome ? $dateCome->format('d.m.Y') : null;
        $dateLeftFormatted = $dateLeft ? $dateLeft->format('d.m.Y') : null;


        $builder
          ->add('dateCome',   TextType::class,      [ 'required'  => false, 'data' => $dateComeFormatted ])
          ->add('dateLeft',   TextType::class,      [ 'required'  => false, 'data' => $dateLeftFormatted ])
          ->add('submit',     SubmitType::class,    [ 'label'     => 'Проверить наличие мест'])
          ->setMethod('GET')
        ;

        if($options['action_url'])
        {
            $builder->setAction($options['action_url']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('date_come', null);
        $resolver->setDefault('date_left', null);
        $resolver->setDefault('csrf_protection', false );
        $resolver->setDefault('action_url', null);
    }
}
