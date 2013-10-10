<?php

namespace Endereco\Model;

class Logradouro {
    public $logcodigo;
    public $nome;
    public $cidcodigo;
    
    public function exchangeArray($data) {
        $this->logcodigo = (isset($data['logcodigo'])) ? $data['logcodigo'] : null;
        $this->nome      = (isset($data['nome'])) ? $data['nome'] : null;
        $this->cidcodigo = (isset($data['cidcodigo'])) ? $data['cidcodigo'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}
