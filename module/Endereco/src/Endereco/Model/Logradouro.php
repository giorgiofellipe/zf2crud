<?php

namespace Endereco\Model;

class Logradouro {
    public $logcodigo;
    public $nome;
    public $baicodigo;
    
    public function exchangeArray($data) {
        $this->logcodigo = (isset($data['logcodigo'])) ? $data['logcodigo'] : null;
        $this->nome      = (isset($data['nome'])) ? $data['nome'] : null;
        $this->baicodigo = (isset($data['baicodigo'])) ? $data['baicodigo'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}
