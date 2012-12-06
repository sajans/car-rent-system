<?php

class Admin_Model_Booking {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Admin_Model_DbTable_Booking');
        }
        return $this->_dbTable;
    }

    public function getAll() {
        $result = $this->getDbTable()->fetchAll("del='N'");
        return $result->toArray();
    }

    public function add($formData) {
        $data = $formData['extra_id'];
        unset($formData["extra_id"]);
        $lastId = $this->getDbTable()->insert($formData);
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        $arr = array();
       $extraBooking = new Admin_Model_Extrabooking(); 
        foreach($data as $key=>$val){
            $arr['extra_id'] = $key;
            $arr['booking_id'] = $lastId;
            $extraBooking->add($arr);
        }
        return $lastId;
    }

}

?>
