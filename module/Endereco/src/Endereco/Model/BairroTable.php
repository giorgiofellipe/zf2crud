<?php

namespace Endereco\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;

class BairroTable extends AbstractTableGateway{
    
    protected $table = 'bairro';
    protected $tableGateway;
    
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new \Zend\Db\ResultSet\HydratingResultSet();
 
        $this->initialize();
    }
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        $resultSet->buffer();
        return $resultSet;
    }
    /*
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Logradouro());
        $this->initialize();
    }
    
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }*/
    
    public function getBairro($baicodigo)
    {
        $baicodigo = (int) $baicodigo;
        $rowset = $this->select(array(
            'baicodigo' => $baicodigo,
        ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $baicodigo");
        }
        return $row;
    }
    
    public function saveBairro(\Endereco\Entity\Bairro $bairro)
    {
        $data = array(
            'nome' => $bairro->nome,
            'cidcodigo' => $bairro->cidcodigo,
        );
        $baicodigo = (int) $bairro->baicodigo;
        if ($baicodigo == 0) {
            $this->insert($data);
        } elseif ($this->getLogradouro($baicodigo)) {
            $this->update(
                $data,
                array(
                    'baicodigo' => $baicodigo,
                )
            );
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function deleteBairro($baicodigo)
    {
        $this->delete(array(
            'baicodigo' => $baicodigo,
        ));
    }
}
