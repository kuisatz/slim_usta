<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL;

/**
 * Business Layer class for report Configuration entity
 */
class InfoCenterPictures extends \BLL\BLLSlim {

    /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }

    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        return $DAL->insert($params);
    }

    /**
     * Data update function 
     * @param array $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        return $DAL->update(  $params);
    }

    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');     
        $resultSet =  $DAL->getAll($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        $resultSet = $DAL->fillGrid($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);
        return $resultSet['resultSet'];
    }

    /**
     *  
     * @param array  $params
     * @return array
     */
    public function fillMainPictures($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        $resultSet = $DAL->fillMainPictures($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillMainCenterPictures($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        $resultSet = $DAL->fillMainCenterPictures($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillMainOfisPictures($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('infoCenterPicturesPDO');
        $resultSet = $DAL->fillMainOfisPictures($params);
        return $resultSet['resultSet'];
    }
    
    
    
}
