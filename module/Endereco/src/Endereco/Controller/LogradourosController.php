<?php

namespace Endereco\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Endereco\Model\Logradouro;

class LogradourosController extends AbstractActionController
{
    protected $logradouroTable;
    protected $_createLogradouroForm;

    protected function _getCreateLogradouroForm()
    {
        if (!$this->_createLogradouroForm) {
            $this->_setCreateLogradouroForm(
                $this->getServiceLocator()->get('novo_logradouro_form')
            );
        }
        return $this->_createLogradouroForm;
    }

    protected function _setCreateLogradouroForm(\Zend\Form\Form $createLogradouroForm)
    {
        $this->_createLogradouroForm = $createLogradouroForm;
    }
    
    // GET /contatos
    public function indexAction() {
        $sm = $this->getServiceLocator();
        if (!$this->logradouroTable) {
            $this->logradouroTable = new \Endereco\Model\LogradouroTable (
                $sm->get('LogradouroTableGateway')
            );
        }
        $logradouros = $this->logradouroTable->fetchAll();
        
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Iterator($logradouros));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($this->params()->fromRoute('perPage', 10))
                ->setPageRange(7);
 
        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    // GET /contatos/novo
    public function novoAction()
    {
        $form = $this->_getCreateLogradouroForm();
        $Logradouro = new Logradouro();
        $form->bind($Logradouro);
        $sm = $this->getServiceLocator();
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados da tabela no banco
                // 1 - solicitar serviço para pegar o model responsável pela adição
                $LogradouroTable = $sm->get('Endereco\Model\LogradouroTable');
                $LogradouroTable->setDbAdapter($sm->get('\Zend\Db\Adapter\Adapter'));
                
                // 2 - inserir dados no banco pelo model
                $LogradouroTable->saveLogradouro($Logradouro);
                 
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Logradouro criado com sucesso");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('logradouros');
            } else {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Erro ao criar logradouro");

                // redirecionar para action novo no controllers contatos
                return $this->redirect()->toRoute('logradouros', array('action' => 'novo'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    // GET /contatos/detalhes/id
    public function detalhesAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Logradouro não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('logradouros');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        $sm = $this->getServiceLocator();
        $LogradouroTable = $sm->get('Endereco\Model\LogradouroTable');
        $LogradouroTable->setDbAdapter($sm->get('\Zend\Db\Adapter\Adapter'));
                
        // 2 - solicitar form com dados desse contato encontrado
        //$form = $this->_getCreateLogradouroForm();
        $Logradouro = new Logradouro();
        $Logradouro->exchangeArray($LogradouroTable->getLogradouro($id));
        // formulário com dados preenchidos
        //$form->bind($Logradouro);
        
        // dados eviados para detalhes.phtml
        return array('id' => $id, 'logradouro' => $Logradouro);
    }

    // GET /contatos/editar/id
    public function editarAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        $sm = $this->getServiceLocator();
        $LogradouroTable = $sm->get('Endereco\Model\LogradouroTable');
        $LogradouroTable->setDbAdapter($sm->get('\Zend\Db\Adapter\Adapter'));

        // 2 - solicitar form com dados desse contato encontrado
        $form = $this->_getCreateLogradouroForm();
        $Logradouro = new Logradouro();
        
        $Logradouro->exchangeArray($LogradouroTable->getLogradouro($id));
        $form->bind($Logradouro);
        
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados da tabela no banco
                // 1 - solicitar serviço para pegar o model responsável pela adição
                $LogradouroTable = $sm->get('Endereco\Model\LogradouroTable');
                $LogradouroTable->setDbAdapter($sm->get('\Zend\Db\Adapter\Adapter'));
                
                // 2 - inserir dados no banco pelo model
                $LogradouroTable->saveLogradouro($Logradouro);
                 
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Logradouro criado com sucesso");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('logradouros');
            } else {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Erro ao editar logradouro");

                // redirecionar para action novo no controllers contatos
                return $this->redirect()->toRoute('logradouros', array('action' => 'novo'));
            }
        } else {
           

            // se id = 0 ou não informado redirecione para contatos
            if (!$id) {
                // adicionar mensagem de erro
                $this->flashMessenger()->addMessage("Logradouro não encotrado");

                // redirecionar para action index
                return $this->redirect()->toRoute('logradouros');
            }

            // dados eviados para editar.phtml
            return array('id' => $id, 'form' => $form);
        }
    }

    // DELETE /contatos/deletar/id
    public function deletarAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Logradouro não encotrado");

        } else {
            $sm = $this->getServiceLocator();
            // aqui vai a lógica para deletar o contato no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            $LogradouroTable = $sm->get('Endereco\Model\LogradouroTable');
            $LogradouroTable->setDbAdapter($sm->get('\Zend\Db\Adapter\Adapter'));
            // 2 - deleta contato
            $LogradouroTable->deleteLogradouro($id);
                    
            // adicionar mensagem de sucesso
            $this->flashMessenger()->addSuccessMessage("Logradouro $id deletado com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('logradouros');
    }
}