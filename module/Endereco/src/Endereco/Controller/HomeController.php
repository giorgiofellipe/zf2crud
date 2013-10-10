<?php

namespace Endereco\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractActionController {

    public function indexAction() {
        //return new ViewModel();
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