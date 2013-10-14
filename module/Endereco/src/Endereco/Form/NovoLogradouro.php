<?php

namespace Endereco\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

class NovoLogradouro extends ProvidesEventsForm
{
    //public function __construct()
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        parent::__construct('Logradouro');

        $this->setAttribute('method', 'post')
             ->setHydrator(new ObjectPropertyHydrator(false))
             ->setInputFilter(new InputFilter());
        /*
        $this->add(array(
            'type' => 'Endereco\Form\LogradouroFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));*/
        
        $this->add(array(
            'name' => 'nome',
            'options' => array(
                'label' => 'Nome'
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'placeholder' => 'Nome do logradouro',
            )
        ));
        
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'bairro',
            'options' => array(
                'label' => 'Bairro',
                'object_manager' => $em,
                'target_class' => 'Endereco\Entity\Bairro',
                'property' => 'nome',
                'find_method'    => array(
                'name'   => 'findBy',
                'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('nome' => 'ASC'),
                    ),
                ),
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'btn-group'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Enviar',
                'class' => 'btn btn-default'
            )
        ));
    }
}