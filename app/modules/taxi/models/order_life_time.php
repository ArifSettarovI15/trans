<?php
class TaxiLifeColumns extends DbDataColumns{

    private $id;
    private $order_id;
    private $order_status;
    private $order_time;
    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);
        $this->setOrderId();
        $this->getOrderId()->setName('order_id');
        $this->getOrderId()->setType(TYPE_UINT);
        $this->setOrderStatus();
        $this->getOrderStatus()->setName('order_status');
        $this->getOrderStatus()->setType(TYPE_UINT);
        $this->setOrderTime();
        $this->getOrderTime()->setName('order_time');
        $this->getOrderTime()->setType(TYPE_STR);
    }
    public function getId(){
        return $this->id;
    }
    public function setId(){
        $this->id = new DbColumn();
    }
    public function getOrderId(){
        return $this->order_id;
    }
    public function setOrderId(){
        $this->order_id = new DbColumn();
    }
    public function getOrderStatus(){
        return $this->order_status;
    }
    public function setOrderStatus(){
        $this->order_status = new DbColumn();
    }
    public function getOrderTime(){
        return $this->order_time;
    }
    public function setOrderTime(){
        $this->order_time = new DbColumn();
    }
}

class TaxiLifeModel extends DbDataModel{
    public $columns_where;
    public $columns_update;
    public function InitDop()
    {
        $this->setTableName('taxi_orders_life');
        $this->setTableItemPrefix('life_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_update = new TaxiLifeColumns();
        $this->columns_where = new TaxiLifeColumns();
    }
}

class TaxiLife extends DbData{
    public $model;
    public function CreateModel()
    {
        $this->model = new TaxiLifeModel();
    }
    public function getSubmitedDesc(){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->addWhereCustom("life_order_status=4");
        $this->model->setOrderBy('life_order_time DESC');
        return $this->GetList();
    }
    public function getSubmitedCount(){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->addWhereCustom("life_order_status=4");
    }
}
