<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 */
class SysCenters extends \DAL\DalSlim {

    /**    
     * @author Okan CIRAN
     * @ sys_centers tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  25.01.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
          try {            
        } catch (\PDOException $e /* Exception $e */) {            
        }
    } 

    /**     
     * @author Okan CIRAN
     * @ sys_centers tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  25.01.2016    
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            
        } catch (\PDOException $e /* Exception $e */) {
           
        }
    }

    /**    
     * @author Okan CIRAN
     * @ sys_centers tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  25.01.2016
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {        
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $kontrol = $this->haveRecords($params); 
            if (!\Utill\Dal\Helper::haveRecord($kontrol)) { 
                $sql = "
                INSERT INTO sys_centers(
                    name,name_eng,description,description_eng,user_id)
                VALUES (
                        :name,
                        :name_eng,  
                        :description,   
                        :description_eng,  
                        :user_id 
                                             )   ";
                $statement = $pdo->prepare($sql); 
               // echo debugPDO($sql, $params);
                $result = $statement->execute();
                $insertID = $pdo->lastInsertId('sys_centers_id_seq');
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
            } else {  
                $errorInfo = '23505'; 
                 $pdo->rollback();
                $result= $kontrol;  
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**    
     * @author Okan CIRAN
     * @ sys_centers tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 15.01.2016
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }
            $sql = " 
            SELECT  
                name as name , 
                '" . $params['name'] . "' AS value , 
                name ='" . $params['name'] . "' AS control,
                concat(name , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM sys_centers                
            WHERE LOWER(name) = LOWER('" . $params['name'] . "')"  
                    . $addSql . " 
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);       
       //   echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);             
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**   
     * @author Okan CIRAN
     * sys_centers tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  25.01.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');           
            $pdo->beginTransaction();     
            $kontrol = $this->haveRecords($params); 
            if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                $sql = "
                UPDATE sys_centers
                SET  
                    name = :name,
                    name_eng = :name_eng,  
                    description = :description,   
                    description_eng = :description_eng,   
                    user_id = :user_id                    
                WHERE id = " . intval($params['id']);
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':name', $params['name'], \PDO::PARAM_STR);  
                $statement->bindValue(':name_eng', $params['name_eng'], \PDO::PARAM_STR); 
                $statement->bindValue(':description', $params['description'], \PDO::PARAM_STR); 
                $statement->bindValue(':description_eng', $params['description_eng'], \PDO::PARAM_STR); 
                $statement->bindValue(':user_id', $params['user_id'], \PDO::PARAM_INT); 
                $update = $statement->execute();
                $affectedRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
            } else {                
                // 23505 	unique_violation
                $errorInfo = '23505';// $kontrol ['resultSet'][0]['message'];  
                $pdo->rollback();
                $result= $kontrol;            
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
 
    /**  
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_centers tablosundan kayıtları döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
        if (isset($args['page']) && $args['page'] != "" && isset($args['rows']) && $args['rows'] != "") {
            $offset = ((intval($args['page']) - 1) * intval($args['rows']));
            $limit = intval($args['rows']);
        } else {
            $limit = 10;
            $offset = 0;
        }

        $sortArr = array();
        $orderArr = array();
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "id, parent_id";            
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            //print_r($orderArr);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {        
            $order = "ASC";
        }
 
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
            SELECT 
                id, 
                main_group, 
                first_group, 
                second_group,  
                name,  
                deleted, 
                parent_id, 
                active, 
                user_id, 
                language_parent_id, 
                language_code,
                language_name, 
                state_deleted,  
                state_active,  
                username FROM (
                        SELECT 
                            a.id, 
                            a.main_group, 
                            a.first_group, 
                            a.second_group,  
                            COALESCE(NULLIF(a.description, ''), a.description_eng) AS name,  
                            a.deleted, 
                            a.parent_id, 
                            a.active, 
                            a.user_id, 
                            a.language_parent_id, 
                            a.language_code,
                            COALESCE(NULLIF(l.language_eng, ''), l.language) AS language_name, 
                            sd.description AS state_deleted,  
                            sd1.description AS state_active,  
                            u.username
                        FROM sys_centers a  
                        INNER JOIN sys_centers sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = a.language_code AND sd.deleted = 0 AND sd.active = 0 
                        INNER JOIN sys_centers sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = a.language_code AND sd1.deleted = 0 AND sd1.active = 0
                        INNER JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted = 0 AND l.active = 0 
                        INNER JOIN info_users u ON u.id = a.user_id 
                        WHERE a.deleted =0 AND language_code = '" . $params['language_code'] . "' ) AS asd               
                ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
       
            $statement = $pdo->prepare($sql);     
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            );
            //   echo debugPDO($sql, $parameters);     
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /** 
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_centers tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  25.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $whereSQL = '';
            $whereSQL1 = ' WHERE a1.deleted =0 ';
            $whereSQL2 = ' WHERE a2.deleted =1 ';            
            $sql = "
                SELECT 
                    COUNT(a.id) AS COUNT ,
                    (SELECT COUNT(a1.id) FROM sys_centers a1  
                    INNER JOIN sys_centers sd1x ON sd1x.main_group = 15 AND sd1x.first_group= a1.deleted AND sd1x.language_code = 'tr' AND sd1x.deleted = 0 AND sd1x.active = 0
                    INNER JOIN sys_centers sd11 ON sd11.main_group = 16 AND sd11.first_group= a1.active AND sd11.language_code = 'tr' AND sd11.deleted = 0 AND sd11.active = 0                             
                    INNER JOIN info_users u1 ON u1.id = a1.user_id 
                     " . $whereSQL1 . " ) AS undeleted_count, 
                    (SELECT COUNT(a2.id) FROM sys_centers a2
                    INNER JOIN sys_centers sd2 ON sd2.main_group = 15 AND sd2.first_group= a2.deleted AND sd2.language_code = 'tr' AND sd2.deleted = 0 AND sd2.active = 0
                    INNER JOIN sys_centers sd12 ON sd12.main_group = 16 AND sd12.first_group= a2.active AND sd12.language_code = 'tr' AND sd12.deleted = 0 AND sd12.active = 0                             
                    INNER JOIN info_users u2 ON u2.id = a2.user_id 			
                      " . $whereSQL2 . " ) AS deleted_count                        
                FROM sys_centers a
                INNER JOIN sys_centers sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = 'tr' AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_centers sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = 'tr' AND sd1.deleted = 0 AND sd1.active = 0                             
                INNER JOIN info_users u ON u.id = a.user_id 
                " . $whereSQL . "
                    ";
            $statement = $pdo->prepare($sql);
          //  echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /** 
     * @author Okan CIRAN
     * su  an kullanılmıyor
     * @ combobox doldurmak için sys_centers tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGetCenters($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
            $countryID = "91";                            
            if ((isset($params['CountryID']) && $params['CountryID'] != "")) { 
                $countryID = $params['CountryID'];  
            }
            $parentID = "0";                            
            if ((isset($params['PID']) && $params['PID'] != "")) { 
                $parentID = $params['PID'];  
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
             
            $sql = "
                SELECT 
                    a.id,  
                    COALESCE(NULLIF(ax.name, ''), a.name_eng) AS name, 
                    a.name_eng,  
                    COALESCE(NULLIF(ax.description, ''), a.description_eng) AS description, 
                    a.description_eng,  
                    a.folder, 
                    COALESCE(NULLIF(ax.language_id, null), 385) AS language_id  
                FROM sys_centers a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                LEFT JOIN sys_language lx ON lx.id = ".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN sys_centers ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.language_id = lx.id AND ax.deleted =0 AND ax.active =0
                where 
                    a.country_id = ".$countryID." AND   
                    a.deleted =0 AND a.active =0 AND
                    a.language_id = 647 AND 
                    a.language_parent_id =0 AND 
                    a.parent_id =".$parentID."                
                ORDER BY a.priority, COALESCE(NULLIF(ax.name, ''), a.name_eng) 
                " ;
            $statement = $pdo->prepare($sql);
           //  echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC); 
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {        
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /** 
     * @author Okan CIRAN
     * su  an kullanılmıyor
     * @ combobox doldurmak için sys_centers tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGetCentersPictures($params = array()) {
         try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
            $countryID = "91";                            
            if ((isset($params['CountryID']) && $params['CountryID'] != "")) { 
                $countryID = $params['CountryID'];  
            }
            $parentID = "0";                            
            if ((isset($params['PID']) && $params['PID'] != "")) { 
                $parentID = $params['PID'];  
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
             
            
            /*
             SELECT 
	a.id, 
	 
	COALESCE(NULLIF(ax.name, ''), a.name_eng) AS name, 
	a.name_eng,  
	COALESCE(NULLIF(ax.description, ''), a.description_eng) AS description, 
	a.description_eng,  
	--a.language_id,  --  language_parent_id, priority, op_user_id, 
	a.folder, 
	COALESCE(NULLIF(ax.language_id, null), 385) AS language_id  
  FROM sys_centers a
 INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
 LEFT JOIN sys_language lx ON lx.id = 647 AND lx.deleted =0 AND lx.active =0
 LEFT JOIN sys_centers ax ON  (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.language_id = lx.id AND ax.deleted =0 AND ax.active =0

 where a.language_id = 647 AND a.deleted =0 AND a.active =0 and a.language_parent_id =0   
  and a.parent_id =0

             */
            
            $sql = "
                SELECT 
                    a.id,  
                    COALESCE(NULLIF(ax.name, ''), a.name_eng) AS name, 
                    a.name_eng,  
                    COALESCE(NULLIF(ax.description, ''), a.description_eng) AS description, 
                    a.description_eng,  
                    a.folder, 
                    COALESCE(NULLIF(ax.language_id, null), 385) AS language_id  
                FROM sys_centers a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                LEFT JOIN sys_language lx ON lx.id = ".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN sys_centers ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.language_id = lx.id AND ax.deleted =0 AND ax.active =0
                where 
                    a.country_id = ".$countryID." AND   
                    a.deleted =0 AND a.active =0 AND
                    a.language_id = 647 AND 
                    a.language_parent_id =0 AND 
                    a.parent_id =".$parentID."                
                ORDER BY a.priority, COALESCE(NULLIF(ax.name, ''), a.name_eng) 
                " ;
            $statement = $pdo->prepare($sql);
           //  echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC); 
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {        
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
 
    
    
}
    