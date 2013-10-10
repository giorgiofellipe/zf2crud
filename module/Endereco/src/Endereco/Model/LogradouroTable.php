<?php

namespace Endereco\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;

class LogradouroTable extends AbstractTableGateway{
    
    protected $table = 'logradouro';
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
    
    public function getLogradouro($logcodigo)
    {
        $logcodigo = (int) $logcodigo;
        $rowset = $this->select(array(
            'logcodigo' => $logcodigo,
        ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $logcodigo");
        }
        return $row;
    }
    
    public function saveLogradouro(Logradouro $logradouro)
    {
        $data = array(
            'nome' => $logradouro->nome,
            'cidcodigo' => $logradouro->cidcodigo,
        );
        $logcodigo = (int) $logradouro->logcodigo;
        if ($logcodigo == 0) {
            $this->insert($data);
        } elseif ($this->getLogradouro($logcodigo)) {
            $this->update(
                $data,
                array(
                    'logcodigo' => $logcodigo,
                )
            );
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function deleteLogradouro($logcodigo)
    {
        $this->delete(array(
            'logcodigo' => $logcodigo,
        ));
    }
}
