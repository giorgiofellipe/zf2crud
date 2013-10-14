<?php

namespace Endereco\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
/** @ORM\Entity 
 *  @ORM\Table(name="logradouro")
 */
class Logradouro {
    protected $inputFilter;
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    * @ORM\Column(type="integer")
    */
    protected $logcodigo = null;
    /** @ORM\Column(type="string") */
    protected $nome;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Bairro", inversedBy="logradouros")
     * @ORM\JoinColumn(name="baicodigo", referencedColumnName="baicodigo")
     */
    protected $bairro;
    
    public function exchangeArray($data) {
        $this->logcodigo = (isset($data['logcodigo'])) ? $data['logcodigo'] : null;
        $this->nome      = (isset($data['nome'])) ? $data['nome'] : null;
        $this->bairro = (isset($data['bairro'])) ? $data['bairro'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
     /**
    * Set input method
    *
    * @param InputFilterInterface $inputFilter
    */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
    * Get input filter
    *
    * @return InputFilterInterface
    */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    public function getLogcodigo() {
        return $this->logcodigo;
    }

    public function setLogcodigo($logcodigo) {
        $this->logcodigo = $logcodigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

}
