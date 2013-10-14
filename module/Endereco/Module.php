<?php
namespace Endereco;

use Endereco\Model\Logradouro;
use Endereco\Model\LogradouroTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Register View Helper
     */
    public function getViewHelperConfig()
    {
        return array(
            # registrar View Helper com injecao de dependecia
            'factories' => array(
                'menuAtivo'  => function($sm) {
                    return new View\Helper\MenuAtivo($sm->getServiceLocator()->get('Request'));
                },
                'message' => function($sm) {
                    return new View\Helper\Message($sm->getServiceLocator()->get('ControllerPluginManager')->get('flashmessenger'));
                },
            )
        );
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'novo_logradouro_form' => function($sm) {
                    $form = new \Endereco\Form\NovoLogradouro();
                    return $form;
                },
                'Endereco\Model\LogradouroTable' =>  function($sm) {
                    $tableGateway = $sm->get('LogradouroTableGateway');
                    $table = new LogradouroTable($tableGateway);
                    return $table;
                },
            )
        );
    }
}
