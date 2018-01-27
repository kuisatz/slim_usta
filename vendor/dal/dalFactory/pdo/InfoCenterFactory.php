<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\Factory\PDO;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 * @since 11/02/2016
 */
class InfoCenterFactory  implements \Zend\ServiceManager\FactoryInterface{ 
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoCenter  = new \DAL\PDO\InfoCenter();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoCenter -> setSlimApp($slimapp); 
        return $infoCenter; 
    } 
    
}