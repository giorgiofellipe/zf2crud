<?php

namespace Endereco\Form;

use Endereco\Model\Logradouro;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

class LogradouroFieldset extends Fieldset 
    implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('Logradouro');
        $this->setHydrator(new ObjectPropertyHydrator(false))
             ->setObject(new Logradouro());
        $this->setLabel('Logradouro');
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
            'name' => 'cidcodigo',
            'options' => array(
                'label' => 'Cidade'
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'placeholder' => 'Cidade',
            )
        ));
    }
    
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'nome' => array(
                'required' => true,
            ),
            'cidcodigo' => array(
                'required' => true,
            )
        );
    }
}