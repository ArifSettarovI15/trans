<?php


class TaxiRentCarsColumns extends DbDataColumns
{

    private $id;
    private $car_id;
    private $car_year;
    private $car_run;
    private $car_engine_capacity;
    private $car_transmission;
    private $car_city;
    private $car_buy;
    private $car_time;




    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setCarId();
        $this->getCarId()->setName('car_id');
        $this->getCarId()->setType(TYPE_STR);

        $this->setCarYear();
        $this->getCarYear()->setName('car_year');
        $this->getCarYear()->setType(TYPE_UINT);

        $this->setCarRun();
        $this->getCarRun()->setName('car_run');
        $this->getCarRun()->setType(TYPE_UINT);


        $this->setCarEngineCapacity();
        $this->getCarEngineCapacity()->setName('car_engine_capacity');
        $this->getCarEngineCapacity()->setType(TYPE_STR);

        $this->setCarTransmission();
        $this->getCarTransmission()->setName('car_transmission');
        $this->getCarTransmission()->setType(TYPE_STR);

        $this->setCarCity();
        $this->getCarCity()->setName('car_city');
        $this->getCarCity()->setType(TYPE_STR);

        $this->setCarBuy();
        $this->getCarBuy()->setName('car_buy');
        $this->getCarBuy()->setType(TYPE_UINT);

        $this->setCarTime();
        $this->getCarTime()->setName('car_time');
        $this->getCarTime()->setType(TYPE_UNIXTIME);

    }
    public function getId() {
        return $this->id;
    }

    private function setId() {
        $this->id=new DbColumn();
    }

    public function getCarId() {
        return $this->car_id;
    }

    private function setCarId() {
        $this->car_id=new DbColumn();

    }
    public function getCarYear() {
        return $this->car_year;
    }

    private function setCarYear() {
        $this->car_year=new DbColumn();

    }


    public function getCarRun() {
        return $this->car_run;
    }

    private function setCarRun( ) {
        $this->car_run =new DbColumn();
    }


    public function getCarEngineCapacity() {
        return $this->car_engine_capacity;
    }

    private function setCarEngineCapacity( ) {
        $this->car_engine_capacity = new DbColumn();
    }
    public function getCarTransmission() {
        return $this->car_transmission;
    }

    private function setCarTransmission( ) {
        $this->car_transmission = new DbColumn();
    }
    public function getCarCity() {
        return $this->car_city;
    }

    private function setCarCity( ) {
        $this->car_city = new DbColumn();
    }
    public function getCarBuy() {
        return $this->car_buy;
    }

    private function setCarBuy( ) {
        $this->car_buy = new DbColumn();
    }
    public function getCarTime() {
        return $this->car_time;
    }

    private function setCarTime( ) {
        $this->car_time = new DbColumn();
    }

}
class TaxiRentCarsModel extends DbDataModel {

    /**
     * @var  TaxiRentCarsColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiRentCarsColumns $columns_update
     */
    public $columns_update;
    public function InitDop () {
        $this->setTableName('taxi_rent_cars');
        $this->setTableItemPrefix('rent_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiRentCarsColumns();
        $this->columns_update=new TaxiRentCarsColumns();
    }

}

class TaxiRentCars extends  DbData
{

    /**
     * @var  TaxiRentCarsModel $model
     */
    public $model;

    /**
     * @var $model TaxiRentCarsModel
     */
    public function CreateModel() {
        $this->model=new TaxiRentCarsModel();
    }
//    public function PrepareData ($result_item,$full=0) {
//        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
//        $result_item['car_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');
//
//        return $result_item;
//    }
    public function GetItemById ($id,$full=0){
        $this->CreateModel();
        if ($full) {
            $this->model->setSelectField($this->model->getTableName().'.*');
//            $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        }
        $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem($full);
    }
    public function GetItemByCarId ($id){
        $this->CreateModel();
//        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->columns_where->getCarId()->setValue($id);
        return $this->GetItem();
    }
    public function getRentCars($all=false, $count=20, $start=0, $filter=1) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->setStart($start);
        $this->model->setCount($count);
        return $this->GetList();
    }
    public function getCount($filter=1){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".rent_id");

        $this->model->addWhereCustom('rent_car_buy='.$this->db->sql_prepare($filter));

        return $this->GetTotal();
    }
    public function addNewRentCar($car_id, $car_year=2020, $car_run=45000,$car_e_c=1.6){
        $this->CreateModel();
        if ($this->GetItemByCarId($car_id)){
            return false;
        }else{
        $this->model->columns_update->getCarId()->setValue($car_id);
        $this->model->columns_update->getCarYear()->setValue($car_year);
        $this->model->columns_update->getCarRun()->setValue($car_run);
        $this->model->columns_update->getCarEngineCapacity()->setValue($car_e_c);
        return $this->Insert();
        }
    }
    public function PrepareData ($result_item,$full=0) {
        $result_item = $this->registry->files->FilePrepare($result_item,'icon_');
        $result_item['car_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',1,'icon_');
        return $result_item;
    }
}
