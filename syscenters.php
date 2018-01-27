<?php

// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/* $app = new \Slim\Slim(array(
  'mode' => 'development',
  'debug' => true,
  'log.enabled' => true,
  )); */

$app = new \Slim\SlimExtended(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::INFO,
    'exceptions.rabbitMQ' => true,
    'exceptions.rabbitMQ.logging' => \Slim\SlimExtended::LOG_RABBITMQ_FILE,
    'exceptions.rabbitMQ.queue.name' => \Slim\SlimExtended::EXCEPTIONS_RABBITMQ_QUEUE_NAME
        ));

/**
 * "Cross-origion resource sharing" kontrolüne izin verilmesi için eklenmiştir
 * @author Okan CIRAN
 * @since 25.10.2017
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC());
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());

 
  
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/FillGetCenters_syscenters/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysCentersBLL'); 
    
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    } 
    $vCountryID = NULL;
    if (isset($_GET['coid'])) {
        $stripper->offsetSet('coid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['coid']));
    }  
    $vPID = NULL;
    if (isset($_GET['pid'])) {
        $stripper->offsetSet('pid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['pid']));
    }  
     
    $stripper->strip();
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   
    if ($stripper->offsetExists('coid')) 
        {$vCountryID = $stripper->offsetGet('coid')->getFilterValue(); }  
    if ($stripper->offsetExists('pid')) 
        {$vPID = $stripper->offsetGet('pid')->getFilterValue(); }  
    
   
    $resData = $BLL->fillGetCenters(array( 
        'url' => $_GET['url'], 
        'LanguageID' => $vLanguageID, 
        'CountryID' => $vCountryID, 
        'PID' => $vPID, 
        ));
    
     $menus = array();
    foreach ($resData as $menu){
        $menus[]  = array(
            "id" => $menu["id"],  
            "name" => html_entity_decode($menu["name"]),
            "name_eng" => html_entity_decode($menu["name_eng"]),
            "description" => html_entity_decode($menu["description"]),
            "description_eng" => html_entity_decode($menu["description_eng"]),
          //  "folder" => html_entity_decode($menu["folder"]),
          //  "language_id" =>  ($menu["language_id"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
}
);
  
 
  /**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/FillGetCentersPictures_syscenters/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysCentersBLL'); 
    
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    }   
    $vCountryID = NULL;
    if (isset($_GET['coid'])) {
        $stripper->offsetSet('coid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['coid']));
    }  
    $vCityID = NULL;
    if (isset($_GET['cityID'])) {
        $stripper->offsetSet('cityID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['cityID']));
    } 
     
    $stripper->strip();
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   
    if ($stripper->offsetExists('coid')) 
        {$vCountryID = $stripper->offsetGet('coid')->getFilterValue(); }   
    if ($stripper->offsetExists('cityID')) 
        {$vCityID = $stripper->offsetGet('cityID')->getFilterValue(); }   
    
   
    $resData = $BLL->fillMainCityBorough(array( 
        'url' => $_GET['url'], 
        'LanguageID' => $vLanguageID, 
        'CountryID' => $vCountryID, 
        'CityID' => $vCityID, 
        ));
    
     $menus = array();
    foreach ($resData as $menu){
        $menus[]  = array(
            "id" => $menu["id"],  
            "name" => html_entity_decode($menu["name"]),
          //  "priority" =>  ($menu["priority"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
}
);
  
$app->run();
