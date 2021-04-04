<?php

class TaxiRoutesColumns extends DbDataColumns {

    private $id;
    private $status;
    private $start;
    private $end;
    private $km;
    private $time;
    private $views;
    private $text_before;
    private $text_after;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setStatus();
        $this->getStatus()->setName('status');
        $this->getStatus()->setType(TYPE_BOOL);

        $this->setStart();
        $this->getStart()->setName('start');
        $this->getStart()->setType(TYPE_UINT);

        $this->setEnd();
        $this->getEnd()->setName('end');
        $this->getEnd()->setType(TYPE_UINT);

        $this->setKm();
        $this->getKm()->setName('km');
        $this->getKm()->setType(TYPE_UINT);

        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_UINT);

        $this->setViews();
        $this->getViews()->setName('time');
        $this->getViews()->setType(TYPE_UINT);

        $this->setTextBefore();
        $this->getTextBefore()->setName('text_before');
        $this->getTextBefore()->setType(TYPE_UINT);

        $this->setTextAfter();
        $this->getTextAfter()->setName('text_after');
        $this->getTextAfter()->setType(TYPE_UINT);
    }
    /**
     * @return DbColumn
     */
    public function getId() {
        return $this->id;
    }

    private function setId() {
        $this->id=new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getStatus() {
        return $this->status;
    }

    private function setStatus() {
        $this->status=new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getStart()
    {
        return $this->start;
    }

    private function setStart()
    {
        $this->start = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getEnd()
    {
        return $this->end;
    }


    private function setEnd()
    {
        $this->end = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getKm()
    {
        return $this->km;
    }

    private function setKm()
    {
        $this->km = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getTime()
    {
        return $this->time;
    }

    private function setTime()
    {
        $this->time = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getViews()
    {
        return $this->views;
    }


    private function setViews()
    {
        $this->views = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getTextBefore()
    {
        return $this->text_before;
    }

    private function setTextBefore()
    {
        $this->text_before = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getTextAfter()
    {
        return $this->text_after;
    }

    public function setTextAfter()
    {
        $this->text_after = new DbColumn();
    }

}


class TaxiRoutesModel extends DbDataModel {

    /**
     * @var  TaxiRoutesColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiRoutesColumns $columns_update
     */
    public $columns_update;


    public function InitDop () {
        $this->setTableName('`taxi_routes`');
        $this->setTableItemPrefix('route_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiRoutesColumns();
        $this->columns_update=new TaxiRoutesColumns();
    }
}

class TaxiRoutes extends  DbData
{

    /**
     * @var  TaxiRoutesModel $model
     */
    public $model;

    /**
     * @var $model TaxiRoutesModel
     */
    public function CreateModel () {
        $this->model=new TaxiRoutesModel();
    }


    public function GetItemById ($id,$full=0){
        $this->CreateModel();
        $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem($full);
    }


    public function PrepareData ($result_item,$full=0) {
        if ($full==1) {
            $result_item['route_text1'] = $this->registry->text->GetText($result_item['route_text_before']);
            $result_item['route_text2'] = $this->registry->text->GetText($result_item['route_text_after']);
        }
        return $result_item;
    }

    public function getRoutes() {
        $this->CreateModel();
        $this->model->columns_where->getStatus()->setValue(true);
        $this->model->setOrderBy('route_start,route_end');
        return $this->GetList();
    }
}
