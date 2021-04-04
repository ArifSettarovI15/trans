<?php

class TaxiReviewsColumns extends DbDataColumns{
    private $id;
    private $uname;
    private $uemail;
    private $comment;
    private $class;
    private $rating;
    private $photo_id;
    private $status;
    private $time;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setUName();
        $this->getUName()->setName('uname');
        $this->getUName()->setType(TYPE_STR);

        $this->setUEmail();
        $this->getUEmail()->setName('uemail');
        $this->getUEmail()->setType(TYPE_STR);

        $this->setComment();
        $this->getComment()->setName('comment');
        $this->getComment()->setType(TYPE_STR);

        $this->setClass();
        $this->getClass()->setName('class');
        $this->getClass()->setType(TYPE_UINT);

        $this->setRating();
        $this->getRating()->setName('rating');
        $this->getRating()->setType(TYPE_UINT);

        $this->setPhoto();
        $this->getPhoto()->setName('icon');
        $this->getPhoto()->setType(TYPE_UINT);

        $this->setStatus();
        $this->getStatus()->setName('status');
        $this->getStatus()->setType(TYPE_UINT);
        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_STR);
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
    public function getUName() {
        return $this->uname;
    }

    private function setUName( ) {
        $this->uname=new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getUEmail() {
        return $this->uemail;
    }

    private function setUEmail( ) {
        $this->uemail =new DbColumn();
    }


    /**
     * @return DbColumn
     */
    public function getComment() {
        return $this->comment;
    }

    private function setComment( ) {
        $this->comment = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getClass() {
        return $this->class;
    }

    private function setClass( ) {
        $this->class = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getRating()
    {
        return $this->rating;
    }

    private function setRating()
    {
        $this->rating = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getPhoto()
    {
        return $this->photo_id;
    }
    public function setPhoto()
    {
        $this->photo_id = new DbColumn();
    }
    /**
     * @return DbColumn
     */
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus()
    {
        $this->status = new DbColumn();
    }
    /**
     * @return DbColumn
     */
    public function getTime()
    {
        return $this->time;
    }
    public function setTime()
    {
        $this->time = new DbColumn();
    }

}

class TaxiReviewsModel extends DbDataModel
{

    /**
     * @var  TaxiReviewsColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiReviewsColumns $columns_update
     */
    public $columns_update;


    public function InitDop()
    {
        $this->setTableName('`taxi_reviews`');
        $this->setTableItemPrefix('review_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where = new TaxiReviewsColumns();
        $this->columns_update = new TaxiReviewsColumns();
    }
}
class TaxiReviews extends DbData{
    /**
     * @var  TaxiReviewsModel $model
     */
    public $model;

    /**
     * @var $model TaxiReviewsModel
     */
    public function CreateModel () {
        $this->model=new TaxiReviewsModel();
    }

    public function GetItemFromId($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        $this->model->columns_where->getId()->setValue($id);
	    $this->model->columns_where->getStatus()->setValue(2);
        return $this->GetItem();
    }

    public function GetItemFromIdAdmin($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
	    $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem();
    }

    public function getReviewsWithoutid($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
	    $this->model->columns_where->getStatus()->setValue(2);
	    $this->model->columns_where->getId()->notValue($id);
        $this->model->SetJoinImage('icon',$this->model->GetTableItemName('icon'));
        return $this->GetList();
    }
    public function getReviewsPublicForSlider($limit=20)
    {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName() . '.*, taxi_classes.class_title');
        $this->model->setJoin("LEFT JOIN taxi_classes ON taxi_reviews.review_class=taxi_classes.class_id");
	    $this->model->columns_where->getStatus()->setValue(2);
        $this->model->setOrderBy('review_id DESC');
        $this->model->setCount($limit);
        return $this->GetList();
    }

    public function getReviews($all=false, $order_way=false, $order_by=false,$start=0, $limit=20)
    {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName() . '.*, taxi_classes.*');

        $this->model->setJoin("LEFT JOIN taxi_classes ON taxi_reviews.review_class=taxi_classes.class_id");
        $this->model->SetJoinImage('icon', $this->model->GetTableItemName('icon'));
        if ($all == false) {
	        $this->model->columns_where->getStatus()->setValue(2);
        }
        if ($order_by){
            $this->model->setOrderBy('review_rating '.$order_way);
        }
        $this->model->setStart($start);
        $this->model->setCount($limit);

        return $this->GetList();
    }
    public function getReviewsForSlider($limit){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
	    $this->model->columns_where->getStatus()->setValue(2);
        $this->model->setOrderBy("review_rating DESC");
        $this->model->setCount($limit);
        return $this->GetList();
    }

    public function PrepareData ($result_item,$full=0) {
        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
        $result_item['review_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');
        return $result_item;
    }

}
