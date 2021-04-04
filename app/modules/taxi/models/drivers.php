<?php
class TaxiDriversColumns extends DbDataColumns {
    private $id;
    private $name;
    private $data;
    private $phone;
    private $car_id;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setName();
        $this->getName()->setName('name');
        $this->getName()->setType(TYPE_STR);

        $this->setData();
        $this->getData()->setName('data');
        $this->getData()->setType(TYPE_STR);

        $this->setCarId();
        $this->getCarId()->setName('car_id');
        $this->getCarId()->setType(TYPE_UINT);

        $this->setPhone();
        $this->getPhone()->setName('phone');
        $this->getPhone()->setType(TYPE_STR);
    }
    public function getId(){return $this->id;}
    public function getName(){return $this->name;}
    public function getData(){return $this->data;}
    public function getCarId(){return $this->car_id;}
    public function getPhone(){return $this->phone;}

    public function setId() {$this->id = new DbColumn();}
    public function setData() {$this->data = new DbColumn();}
    public function setCarId() {$this->car_id = new DbColumn();}
    public function setPhone() {$this->phone = new DbColumn();}
    public function setName() {$this->name = new DbColumn();}
}

class TaxiDriversModel extends DbDataModel{
    public $columns_update;
    public $columns_where;

    public function InitDop()
    {
        $this->setTableName('taxi_drivers');
        $this->setTableItemPrefix("driver_");
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));

        $this->columns_update = new TaxiDriversColumns();
        $this->columns_where = new TaxiDriversColumns();
    }
}
class TaxiDrivers extends  DbData
{
    public $model;

    public function CreateModel()
    {
        $this->model = new TaxiDriversModel();
    }

    public function getDrivers($start=0, $count=20){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setOrderBy('driver_name ASC');
        $this->model->setStart($start);
        $this->model->setCount($count);
        return $this->GetList();
    }
    public function GetItemById($id)
    {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $result = $this->GetItem();
        $result['driver_data'] = unserialize($result['driver_data']);
        return $result;
    }
    public function getCount(){
        $this->CreateModel();

        return $this->GetTotal();
    }
}