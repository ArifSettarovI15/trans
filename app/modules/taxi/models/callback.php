<?php


class TaxiCallBacksColumns extends DbDataColumns
{

    private $id;
    private $name;
    private $phone;
    private $status;
    private $time;



    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setUName();
        $this->getUName()->setName('name');
        $this->getUName()->setType(TYPE_STR);


        $this->setPhone();
        $this->getPhone()->setName('phone');
        $this->getPhone()->setType(TYPE_STR);

        $this->setStatus();
        $this->getStatus()->setName('status');
        $this->getStatus()->setType(TYPE_UINT);

        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_STR);




    }

    public function getId()
    {
        return $this->id;
    }

    private function setId()
    {
        $this->id = new DbColumn();
    }

    public function getPhone()
    {
        return $this->phone;
    }

    private function setPhone()
    {
        $this->phone = new DbColumn();

    }



    public function getUName()
    {
        return $this->name;
    }

    private function setUName()
    {
        $this->name = new DbColumn();
    }

    public function getStatus()
    {
        return $this->status;
    }

    private function setStatus()
    {
        $this->status = new DbColumn();
    }
    public function getTime()
    {
        return $this->time;
    }

    private function setTime()
    {
        $this->time = new DbColumn();
    }

}

class TaxiCallBacksModel extends DbDataModel
{

    /**
     * @var  TaxiCallBacksColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiCallBacksColumns $columns_update
     */
    public $columns_update;

    public function InitDop()
    {
        $this->setTableName('taxi_callbacks');
        $this->setTableItemPrefix('callback_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where = new TaxiCallBacksColumns();
        $this->columns_update = new TaxiCallBacksColumns();
    }

}

class TaxiCallBacks extends  DbData
{

    /**
     * @var  TaxiCallBacksModel $model
     */
    public $model;

    /**
     * @var $model TaxiCallBacks
     */
    public function CreateModel() {
        $this->model=new TaxiCallBacksModel();

    }

    public function getCount(){
        $this->CreateModel();
        return $this->GetTotal();
    }
    public function getCallbacks($start=1,$per_page=20){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setOrderWay('DESC');
        $this->model->setOrderBy('callback_time');
        $this->model->setStart($start);
        $this->model->setCount($per_page);
        return $this->GetList();
    }
}

