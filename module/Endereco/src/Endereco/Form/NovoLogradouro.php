<?php

namespace Endereco\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

class NovoLogradouro extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct('Logradouro');

        $this->setAttribute('method', 'post')
             ->setHydrator(new ObjectPropertyHydrator(false))
             ->setInputFilter(new InputFilter());
        $this->add(array(
            'type' => 'Endereco\Form\LogradouroFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
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