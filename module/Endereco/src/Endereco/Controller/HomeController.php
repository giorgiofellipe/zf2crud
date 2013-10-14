<?php

namespace Endereco\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractActionController {

    public function indexAction() {
// EXEMPLO DE COMO UTILIZAR DOCTRINE        
//        $objectManager = $this
//            ->getServiceLocator()
//            ->get('Doctrine\ORM\EntityManager');
//
//        $logradouro = new \Endereco\Entity\Logradouro();
//        $logradouro->setNome('Rua de Teste');
//        $logradouro->setBaicodigo(2);
//
//        $objectManager->persist($logradouro);
//        $objectManager->flush();
//
//        var_dump($logradouro);        

        // redirecionar para action index
        return $this->redirect()->toRoute('logradouros');
    }
    
    /**
     * action sobre
     * @return \Zend\View\Model\ViewModel
     */
    public function sobreAction()
    {
        return new ViewModel();
    }

}