<?php

class TaxiTelegramHistoryColumns extends DbDataColumns
{
    private $id;
    private $user_id;
    private $message_data;
    private $command;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setUserId();
        $this->getUserId()->setName('user_id');
        $this->getUserId()->setType(TYPE_STR);

        $this->setMessageData();
        $this->getMessageData()->setName('message_data');
        $this->getMessageData()->setType(TYPE_STR);

        $this->setCommand();
        $this->getCommand()->setName('command');
        $this->getCommand()->setType(TYPE_STR);
    }

    /**
     * @return DbColumn
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId()
    {
        $this->id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getUserId(){
        return $this->user_id;
    }
    public function setUserId()
    {
        $this->user_id = new DbColumn();
    }
    /**
     * @return DbColumn
     */
    public function getMessageData(){
        return $this->message_data;
    }
    public function setMessageData(){
        $this->message_data = new DbColumn();
    }
    /**
     * @return DbColumn
     */
    public function getCommand(){
        return $this->command;
    }
    public function setCommand(){
        $this->command = new DbColumn();
    }
}

class TaxiTelegramHistoryModel extends DbDataModel{
    /**
     * @var TaxiTelegramHistoryColumns
     */
    public $columns_where;
    /**
     * @var TaxiTelegramHistoryColumns
     */
    public $columns_update;

    public function initDop(){
        $this->setTableName('`taxi_telegram_history`');
        $this->setTableItemPrefix('history_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where = new TaxiTelegramHistoryColumns();
        $this->columns_update = new TaxiTelegramHistoryColumns();
    }
}

class TaxiTelegramHistory extends DbData{
    /**
     * @var  TaxiTelegramHistoryModel $model
     */
    public $model;

    /**
     * @var $model TaxiTelegramHistoryModel
     */
    public function CreateModel () {
        $this->model=new TaxiTelegramHistoryModel();
    }
    public function getLastActionById($user_id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().('.*'));
        $this->model->columns_where->getUserId()->setValue($user_id);
        $this->model->setOrderBy('history_id DESC');
        $this->model->setCount(1);
        $result = $this->GetItem();
        if ($result) {
            return $result['history_command'];
        }
        else{
            return 0;
        }
    }
}