<?php


class TaxiRentRequestsColumns extends DbDataColumns
{

    private $id;
    private $data;
    private $name;
    private $phone;
    private $car_id;
    private $time;




    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setData();
        $this->getData()->setName('data');
        $this->getData()->setType(TYPE_STR);

        $this->setName();
        $this->getName()->setName('name');
        $this->getName()->setType(TYPE_STR);

        $this->setPhone();
        $this->getPhone()->setName('phone');
        $this->getPhone()->setType(TYPE_STR);

        $this->setCarId();
        $this->getCarId()->setName('car_id');
        $this->getCarId()->setType(TYPE_UINT);

        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_STR);

    }
    public function getId() {
        return $this->id;
    }

    private function setId() {
        $this->id=new DbColumn();
    }
    public function getData() {
        return $this->data;
    }

    private function setData() {
        $this->data=new DbColumn();
    }
    public function getName() {
        return $this->name;
    }

    private function setName() {
        $this->name=new DbColumn();
    }
    public function getPhone() {
        return $this->phone;
    }

    private function setPhone() {
        $this->phone=new DbColumn();
    }

    public function getCarId() {
        return $this->car_id;
    }

    private function setCarId() {
        $this->car_id=new DbColumn();

    }
    public function getTime() {
        return $this->time;
    }

    private function setTime() {
        $this->time=new DbColumn();

    }

}
class TaxiRentRequestsModel extends DbDataModel {

    /**
     * @var  TaxiRentCarsColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiRentCarsColumns $columns_update
     */
    public $columns_update;
    public function InitDop () {
        $this->setTableName('taxi_rent_requests');
        $this->setTableItemPrefix('rent_req_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiRentRequestsColumns();
        $this->columns_update=new TaxiRentRequestsColumns();
    }

}

class TaxiRentRequests extends  DbData
{

    /**
     * @var  TaxiRentRequestsModel $model
     */
    public $model;

    /**
     * @var $model TaxiRentRequestsModel
     */
    public function CreateModel() {
        $this->model=new TaxiRentRequestsModel();
    }


}
