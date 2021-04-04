<?php

class TaxiClassesColumns extends DbDataColumns {

    private $id;
    private $status;
    private $title;
    private $photo_id;
    private $places;
    private $min_price;
    private $sort;
    private $desc;
    private $price_km;


    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setStatus();
        $this->getStatus()->setName('status');
        $this->getStatus()->setType(TYPE_BOOL);

        $this->setTitle();
        $this->getTitle()->setName('title');
        $this->getTitle()->setType(TYPE_STR);

        $this->setPhotoId();
        $this->getPhotoId()->setName('icon');
        $this->getPhotoId()->setType(TYPE_UINT);

        $this->setPlaces();
        $this->getPlaces()->setName('places');
        $this->getPlaces()->setType(TYPE_UINT);

        $this->setMinPrice();
        $this->getMinPrice()->setName('min_price');
        $this->getMinPrice()->setType(TYPE_UINT);

        $this->setSort();
        $this->getSort()->setName('sort');
        $this->getSort()->setType(TYPE_UINT);

        $this->setDesc();
        $this->getDesc()->setName('desc');
        $this->getDesc()->setType(TYPE_STR);

        $this->setPriceKm();
        $this->getPriceKm()->setName('price_km');
        $this->getPriceKm()->setType(TYPE_UINT);
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

    /**
     * @return DbColumn
     */
    public function getStatus() {
        return $this->status;
    }

    private function setStatus( ) {
        $this->status=new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getTitle() {
        return $this->title;
    }

    private function setTitle( ) {
        $this->title =new DbColumn();
    }


    /**
     * @return DbColumn
     */
    public function getPhotoId() {
        return $this->photo_id;
    }

    private function setPhotoId( ) {
        $this->photo_id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getPlaces()
    {
        return $this->places;
    }

    private function setPlaces()
    {
        $this->places = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getMinPrice()
    {
        return $this->min_price;
    }


    private function setMinPrice()
    {
        $this->min_price = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getSort()
    {
        return $this->sort;
    }

    private function setSort()
    {
        $this->sort =  new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getDesc()
    {
        return $this->desc;
    }

    private function setDesc()
    {
        $this->desc = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getPriceKm()
    {
        return $this->price_km;
    }

    private function setPriceKm()
    {
        $this->price_km = new DbColumn();
    }
}


class TaxiClassesModel extends DbDataModel {

    /**
     * @var  TaxiClassesColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiClassesColumns $columns_update
     */
    public $columns_update;


    public function InitDop () {
        $this->setTableName('`taxi_classes`');
        $this->setTableItemPrefix('class_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiClassesColumns();
        $this->columns_update=new TaxiClassesColumns();
    }
}

class TaxiClasses extends  DbData
{

    /**
     * @var  TaxiClassesModel $model
     */
    public $model;

    /**
     * @var $model TaxiClassesModel
     */
    public function CreateModel () {
        $this->model=new TaxiClassesModel();
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


//    public function PrepareData ($result_item,$full=0) {
//        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
//        $result_item['class_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');
//        $result_item['class_full_url']=BASE_URL.'/classes/'.$result_item['class_id'].'/';
//
//        return $result_item;
//    }
    public function getClassId($class_id){
        $this->createModel();
        $this->model->setSelectField('class_id');
        $class = $this->GetItem($class_id);
        return $class['class_id'];

    }
    public function getClassesSimple(){
        $this->createModel();
        $this->model->setSelectField($this->model->getTableName().'.class_title');
        $this->model->setSelectField($this->model->getTableName().'.class_id');
        //сортировка по classes_sort, проверка status
        return $this->GetList();
    }
    public function getClasses($all=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->setOrderBy('class_sort');
        if ($all==false) {
            $this->model->columns_where->getStatus()->setValue(true);
        }

        return $this->GetList();
    }
}
