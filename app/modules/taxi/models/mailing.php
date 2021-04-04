<?php

class TaxiMailingColumns extends DbDataColumns{

    private $id;
    private $email;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setEmail();
        $this->getEmail()->setName('email');
        $this->getEmail()->setType(TYPE_STR);
    }
    public function getId(){
        return $this->id;
    }
    public function setId(){
        $this->id= new DbColumn();
    }

    public function getEmail(){
        return $this->email;
    }
    public function setEmail(){
        $this->email = new DbColumn();
    }
}

class TaxiMailingModel extends DbDataModel{
    /**
     * @var TaxiMailingColumns $columns_update
     */
    /**
     * @var TaxiMailingColumns $columns_where
     */
    public $columns_update;
    public $columns_where;
    public function InitDop()
    {
        $this->setTableName('taxi_mailing');
        $this->setTableItemPrefix('mailing_');
        $this->setTablePrimaryKey('id');
        $this->columns_update = new TaxiMailingColumns;
        $this->columns_where = new TaxiMailingColumns;
    }
}

class TaxiMailing extends DbData{
    /**
     * @var TaxiMailingModel $model
     */
    public $model;

    /**
     * @var $model TaxiMailingModel
     */

    public function CreateModel()
    {
        $this->model = new TaxiMailingModel();
    }
}