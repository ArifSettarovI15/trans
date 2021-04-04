<?php
class TaxiCarsClassesColumns extends DbDataColumns {

    private $id;
    private $car_id;
    private $class_id;


    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setCarId();
        $this->getCarId()->setName('car_id');
        $this->getCarId()->setType(TYPE_UINT);

        $this->setClassId();
        $this->getClassId()->setName('class_id');
        $this->getClassId()->setType(TYPE_UINT);

    }
    /**
     * @return DbColumn
     */
    public function getId() {
        return $this->id;
    }

    private function setId() {
        $this->id= new DbColumn();
    }
    public function getCarId() {
        return $this->car_id;
    }

    private function setCarId() {
        $this->car_id= new DbColumn();
    }
    public function getClassId() {
        return $this->class_id;
    }

    private function setClassId() {
        $this->class_id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
}
class TaxiCarsClassesModel extends DbDataModel {

    /**
     * @var  TaxiCarsClassesColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiCarsClassesColumns $columns_update
     */
    public $columns_update;


    public function InitDop () {
        $this->setTableName('`taxi_cars_classes`');
        $this->setTableItemPrefix('cc_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiCarsClassesColumns();
        $this->columns_update=new TaxiCarsClassesColumns();
    }
}

class TaxiCarsClasses extends  DbData
{
    /**
     * @var  TaxiCarsClassesModel $model
     */
    public $model;

    /**
     * @var $model TaxiCarsClassesModel
     */
    public function CreateModel () {
        $this->model=new TaxiCarsClassesModel();
    }
    public function getCarClassId($class_id){
        $this->createModel();
        $this->model->setSelectField('cc_car_id');
        $class = $this->GetItem($class_id);
        return $class['cc_id'];
    }
    public function getCarsClasses(){
        $this->createModel();
        return $this->GetList();
    }
    public function newCarClass($car_id, $class_id, $update=false){
        $this->CreateModel();

        if ($update){
            $this->model->columns_where->getCarId()->setValue($car_id);
            $this->model->columns_update->getClassId()->setValue($class_id);
            $this->Update();
        }
        else{
            $this->model->columns_update->getCarId()->setValue($car_id);
            $this->model->columns_update->getClassId()->setValue($class_id);
            $this->Insert();
        }
    }

}