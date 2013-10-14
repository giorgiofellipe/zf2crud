<?php

namespace Endereco\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Bairro {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    * @ORM\Column(type="integer")
    */
    protected $baicodigo = null;
    /** @ORM\Column(type="string") */
    protected $nome;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Cidade", inversedBy="bairros")
     * @ORM\JoinColumn(name="cidcodigo", referencedColumnName="cidcodigo")
     */
    protected $cidade;
    /**
     *
     * @ORM\OneToMany(targetEntity="Logradouro", mappedBy="bairro")
     */
    protected $logradouros;
    
    public function exchangeArray($data) {
        $this->baicodigo = (isset($data['baicodigo'])) ? $data['baicodigo'] : null;
        $this->nome      = (isset($data['nome'])) ? $data['nome'] : null;
        $this->cidcodigo = (isset($data['cidcodigo'])) ? $data['cidcodigo'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function getBaicodigo() {
        return $this->baicodigo;
    }

    public function setBaicodigo($baicodigo) {
        $this->baicodigo = $baicodigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getLogradouros() {
        return $this->logradouros;
    }

    public function setLogradouros($logradouros) {
        $this->logradouros = $logradouros;
    }


}