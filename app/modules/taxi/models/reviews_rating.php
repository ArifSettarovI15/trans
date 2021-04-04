<?php

class TaxiReviewsRatingColumns extends DbDataColumns{

    private $id;
    private $review_id;
    private $comfort;
    private $driver;
    private $clean;
    private $price;
    private $route;


    public function __construct(){
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setReview();
        $this->getReview()->setName('review_id');
        $this->getReview()->setType(TYPE_UINT);

        $this->setComfort();
        $this->getComfort()->setName('comfort');
        $this->getComfort()->setType(TYPE_UINT);

        $this->setDriver();
        $this->getDriver()->setName('driver');
        $this->getDriver()->setType(TYPE_UINT);

        $this->setClean();
        $this->getClean()->setName('clean');
        $this->getClean()->setType(TYPE_UINT);

        $this->setPrice();
        $this->getPrice()->setName('price');
        $this->getPrice()->setType(TYPE_UINT);

        $this->setRoute();
        $this->getRoute()->setName('route');
        $this->getRoute()->setType(TYPE_UINT);
    }

    /**
     * @return DbColumn
     */
    public function getId(){
        return $this->id;
    }

    public function setId(){
        $this->id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getReview(){
        return $this->review_id;
    }

    public function setReview(){
        $this->review_id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getComfort(){
        return $this->comfort;
    }
    public function setComfort(){
        $this->comfort = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getDriver(){
        return $this->driver;
    }
    public function setDriver(){
        $this->driver = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getClean(){
        return $this->clean;
    }
    public function setClean(){
        $this->clean = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getPrice(){
        return $this->price;
    }
    public function setPrice(){
        $this->price = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getRoute(){
        return $this->route;
    }
    public function setRoute(){
        $this->route = new DbColumn();
    }
}

class TaxiReviewsRatingModel extends DbDataModel
{

    /**
     * @var  TaxiReviewsRatingColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiReviewsRatingColumns $columns_update
     */
    public $columns_update;


    public function InitDop()
    {
        $this->setTableName('`taxi_reviews_rating`');
        $this->setTableItemPrefix('rating_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where = new TaxiReviewsRatingColumns();
        $this->columns_update = new TaxiReviewsRatingColumns();
    }
}
class TaxiReviewsRating extends DbData{
    /**
     * @var  TaxiReviewsRatingModel $model
     */
    public $model;

    /**
     * @var $model TaxiReviewsRatingModel
     */
    public function CreateModel () {
        $this->model=new TaxiReviewsRatingModel();
    }
    public function GetRatings($review_id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->addWhereCustom("rating_review_id = ".$this->db->sql_prepare($review_id));
        return $this->GetItem();
    }

    public function InsertRatings($ratings, $review_id){
        $this->CreateModel();
        $this->model->columns_update->getComfort()->setValue($ratings['comfort']);
        $this->model->columns_update->getDriver()->setValue($ratings['driver']);
        $this->model->columns_update->getClean()->setValue($ratings['clean']);
        $this->model->columns_update->getPrice()->setValue($ratings['price']);
        $this->model->columns_update->getRoute()->setValue($ratings['route_know']);
        $this->model->columns_update->getReview()->setValue($review_id);
        $this->Insert();
    }
    public function UpdateRatings($ratings, $review_id){
        $this->CreateModel();
        $this->model->columns_update->getComfort()->setValue($ratings['comfort']);
        $this->model->columns_update->getDriver()->setValue($ratings['driver']);
        $this->model->columns_update->getClean()->setValue($ratings['clean']);
        $this->model->columns_update->getPrice()->setValue($ratings['price']);
        $this->model->columns_update->getRoute()->setValue($ratings['route_know']);
        $this->model->columns_where->getReview()->setValue($review_id);
        return $this->Update();
    }
}
