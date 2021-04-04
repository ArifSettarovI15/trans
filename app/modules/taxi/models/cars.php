<?php


class TaxiCarsColumns extends DbDataColumns
{

    private $id;
    private $title;
    private $class;
    private $power;
    private $photo_id;
    private $rent;



    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setTitle();
        $this->getTitle()->setName('title');
        $this->getTitle()->setType(TYPE_STR);

        $this->setClass();
        $this->getClass()->setName('class');
        $this->getClass()->setType(TYPE_UINT);

        $this->setPower();
        $this->getPower()->setName('power');
        $this->getPower()->setType(TYPE_UINT);


        $this->setPhotoId();
        $this->getPhotoId()->setName('icon');
        $this->getPhotoId()->setType(TYPE_UINT);
        $this->setRent();
        $this->getRent()->setName('rent');
        $this->getRent()->setType(TYPE_UINT);

    }
    public function getId() {
        return $this->id;
    }

    private function setId() {
        $this->id=new DbColumn();
    }
    /**
     * @return DbColumn
     */
    public function getClass() {
        return $this->class;
    }

    private function setClass() {
        $this->class=new DbColumn();

    }
    public function getPower() {
        return $this->power;
    }

    private function setPower() {
        $this->power=new DbColumn();

    }


    public function getTitle() {
        return $this->title;
    }

    private function setTitle( ) {
        $this->title =new DbColumn();
    }


    public function getPhotoId() {
        return $this->photo_id;
    }

    private function setPhotoId( ) {
        $this->photo_id = new DbColumn();
    }
    public function getRent() {
        return $this->rent;
    }

    private function setRent( ) {
        $this->rent = new DbColumn();
    }

}
class TaxiCarsModel extends DbDataModel {

    /**
     * @var  TaxiCarsColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiCarsColumns $columns_update
     */
    public $columns_update;
    public function InitDop () {
        $this->setTableName('taxi_cars');
        $this->setTableItemPrefix('car_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiCarsColumns();
        $this->columns_update=new TaxiCarsColumns();
    }

}

class TaxiCars extends  DbData
{

    /**
     * @var  TaxiCarsModel $model
     */
    public $model;

    /**
     * @var $model TaxiCarsModel
     */
    public function CreateModel() {
        $this->model=new TaxiCarsModel();
    }
    public function PrepareData ($result_item,$full=0) {
        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
        $result_item['car_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');

        return $result_item;
    }
    public function GetItemById ($id,$full=0){
        $this->CreateModel();
        if ($full) {
            $this->model->setSelectField($this->model->getTableName().'.*');
            $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        }
        $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem($full);
    }
    public function getRentCarsIcons(){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.car_id, '.$this->model->getTableName().'.car_icon');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->addWhereCustom('car_rent  = 1');
        return $this->GetList();

    }
    public function getRentCars(){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->addWhereCustom('car_rent  = 1');
        return $this->GetList();

    }
    public function getCars($all=false, $count=20, $start=0, $class_id='all') {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*, taxi_classes.*');
        $this->model->setJoin("LEFT JOIN taxi_classes ON class_id=car_class");
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->setOrderBy('car_title');
        $this->model->setStart($start);
        $this->model->setCount($count);
        return $this->GetList();
    }

    public function getCarsPublicWithPhotosOnly($start=0, $limit=20) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*, taxi_classes.class_title');
        $this->model->setJoin("LEFT JOIN taxi_classes ON class_id=car_class");
        $this->model->addWhereCustom("car_icon!=0");
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->setOrderBy('car_title');
        $this->model->setStart($start);
        $this->model->setCount($limit);
        return $this->GetList();
    }
    public function getCount($class_id='all'){
        $this->CreateModel();
        if ($class_id !='all'){
        	$this->model->columns_where->getClass()->setValue($class_id);
        }
        return $this->GetTotal();


    }
}
