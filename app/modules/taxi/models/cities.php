<?php

class TaxiCitiesColumns extends DbDataColumns {

    private $id;
    private $status;
    private $title;
    private $url;
    private $photo_id;
    private $views;
    private $coor;
    private $pop;
    private $type;
    private $aliases;

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

        $this->setUrl();
        $this->getUrl()->setName('url');
        $this->getUrl()->setType(TYPE_STR);

        $this->setPhotoId();
        $this->getPhotoId()->setName('icon');
        $this->getPhotoId()->setType(TYPE_UINT);

        $this->setViews();
        $this->getViews()->setName('time');
        $this->getViews()->setType(TYPE_UINT);

        $this->setCoor();
        $this->getCoor()->setName('coor');
        $this->getCoor()->setType(TYPE_STR);

        $this->setPop();
        $this->getPop()->setName('pop');
        $this->getPop()->setType(TYPE_BOOL);

        $this->setType();
        $this->getType()->setName('type');
        $this->getType()->setType(TYPE_UINT);

	    $this->setAliases();
	    $this->getAliases()->setName('aliases');
	    $this->getAliases()->setType(TYPE_STR);
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
    public function getUrl()
    {
        return $this->url;
    }

    private function setUrl()
    {
        $this->url = new DbColumn();
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
    public function getCoor()
    {
        return $this->coor;
    }

    private function setCoor()
    {
        $this->coor = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getPop()
    {
        return $this->pop;
    }

	private function setPop()
    {
        $this->pop = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getType()
    {
        return $this->type;
    }

    private function setType()
    {
        $this->type = new DbColumn();
    }

	/**
	 * @return DbColumn
	 */
	public function getAliases() {
		return $this->aliases;
	}

	private function setAliases() {
		$this->aliases = new DbColumn();
	}
}


class TaxiCitiesModel extends DbDataModel {

    /**
     * @var  TaxiCitiesColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiCitiesColumns $columns_update
     */
    public $columns_update;


    public function InitDop () {
        $this->setTableName('`taxi_cities`');
        $this->setTableItemPrefix('city_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiCitiesColumns();
        $this->columns_update=new TaxiCitiesColumns();
    }
}

class TaxiCities extends  DbData
{

    /**
     * @var  TaxiCitiesModel $model
     */
    public $model;

    /**
     * @var $model TaxiCitiesModel
     */
    public function CreateModel () {
        $this->model=new TaxiCitiesModel();
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
    public function GetItemByUrl ($url,$full=0){
        $this->CreateModel();

        if ($full) {
            $this->model->setSelectField($this->model->getTableName().'.*');
            $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        }
        $this->model->columns_where->getUrl()->setValue($url);
        return $this->GetItem($full);
    }

    public function PrepareData ($result_item,$full=0) {
        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
        $result_item['city_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');

        return $result_item;
    }

    public function getCities($all=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        if ($all==false) {
            $this->model->columns_where->getStatus()->setValue(true);
        }
        $this->model->setOrderBy('city_title');

        return $this->GetList();
    }
    public function getCitiesSelect($all=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->setJoin('INNER JOIN taxi_routes WHERE taxi_cities.city_id=taxi_routes.route_start');


        if ($all==false) {
            $this->model->columns_where->getStatus()->setValue(true);
        }
        $this->model->setOrderBy('city_title');

        return $this->GetList();
    }
    public function getOnlyCities($all=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->addWhereCustom('city_type=1');
        $this->model->setOrderBy('city_title');

        return $this->GetList();
    }
    public function getHotels($all=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        if ($all==false) {
            $this->model->columns_where->getStatus()->setValue(true);
        }
        $this->model->columns_where->getType()->setValue('2');
        $this->model->setOrderBy('city_title');
        return $this->GetList();
    }
    public function getPlacesPublicSortByViews($max=12) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*, taxi_prices.price_value');
        $this->model->setJoin("
                        JOIN taxi_routes ON taxi_routes.route_start=113
                        AND taxi_cities.city_id=taxi_routes.route_end
                        LEFT JOIN taxi_prices ON taxi_prices.price_route_id=taxi_routes.route_id");
        $this->model->addWhereCustom('taxi_cities.city_icon!=0');
        $this->model->addWhereCustom("taxi_prices.price_class_id=2");

        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->setOrderBy('city_views DESC');
        $this->model->setCount($max);
        return $this->GetList();
    }

    public function getPlaces($all=false, $views=false) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->columns_where->getType()->setValue('3');
        if ($all==false) {
            $this->model->columns_where->getStatus()->setValue(true);
        }
        if ($views )
        {
            $this->model->setOrderBy('city_views DESC');
        }
        else {
            $this->model->setOrderBy('city_title');
        }
        return $this->GetList();
    }
}
