<?php

namespace Endereco\Controller;

use Endereco\Controller\EntityUsingController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;

class LogradourosController extends EntityUsingController
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
    
    public function indexAction() {
        $logradouros = $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('Endereco\Entity\Logradouro', 't')
            ->orderBy('t.nome', 'ASC');

        $doctrinePaginator = new DoctrinePaginator($logradouros);
        $paginatorAdapter = new PaginatorAdapter($doctrinePaginator);
        $paginator = new Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }
    
    // GET /contatos
    public function indexActionOLD() {
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
    
    public function novoAction() {
        $logradouro = new \Endereco\Entity\Logradouro();

        if ($this->params('id') > 0) {
            $logradouro = $this->getEntityManager()->getRepository('Endereco\Entity\Logradouro')->find($this->params('id'));
        }

        $form = new \Endereco\Form\NovoLogradouro($this->getEntityManager());
        $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'Endereco\Entity\Logradouro'));
        $form->bind($logradouro);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($logradouro->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $em = $this->getEntityManager();

                $em->persist($logradouro);
                $em->flush();

                $this->flashMessenger()->addSuccessMessage('Logradouro salvo');

                return $this->redirect()->toRoute('logradouros');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }
    
    // GET /contatos/novo
    public function novoActionOLD()
    {   
        $form = $this->_getCreateLogradouroForm();
        $objectManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
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
    
    public function detalhesAction() {
        $logradouro = new \Endereco\Entity\Logradouro();

        if ($this->params('id') > 0) {
            $logradouro = $this->getEntityManager()->getRepository('Endereco\Entity\Logradouro')->find($this->params('id'));
        } else {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Logradouro não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('logradouros');
        }
        return new ViewModel(array(
            'logradouro' => $logradouro
        ));
    }

    // GET /contatos/detalhes/id
    public function detalhesActionOLD()
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
        $Logradouro = new \Endereco\Entity\Logradouro();
        $Logradouro->exchangeArray($LogradouroTable->getLogradouro($id));
        // formulário com dados preenchidos
        //$form->bind($Logradouro);
        
        // dados eviados para detalhes.phtml
        return array('id' => $id, 'logradouro' => $Logradouro);
    }

    public function editarAction() {
        return $this->novoAction();
    }
    
    // GET /contatos/editar/id
    public function editarActionOLD()
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

    public function deletarAction() {
        $logradouro = $this->getEntityManager()->getRepository('Endereco\Entity\Logradouro')->find($this->params('id'));

        if ($logradouro) {
            $em = $this->getEntityManager();
            $em->remove($logradouro);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Logradouro deletado');
        }

        return $this->redirect()->toRoute('logradouros');
    }
    
    // DELETE /contatos/deletar/id
    public function deletarActionOLD()
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