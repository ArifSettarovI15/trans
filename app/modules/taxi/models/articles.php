<?php
class TaxiArticlesColumns extends DbDataColumns{
    private $id;
    private $title;
    private $text;
    private $category;
    private $icon;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setTitle();
        $this->getTitle()->setName('title');
        $this->getTitle()->setType(TYPE_STR);

        $this->setText();
        $this->getText()->setName('text');
        $this->getText()->setType(TYPE_STR);

        $this->setCategory();
        $this->getCategory()->setName('category');
        $this->getCategory()->setType(TYPE_UINT);

        $this->setIcon();
        $this->getIcon()->setName('icon');
        $this->getIcon()->setType(TYPE_UINT);


    }
    public function getId(){
        return $this->id;
    }
    public function setId(){
        $this->id = new DbColumn();
    }
    public function getTitle(){
        return $this->title;
    }
    public function setTitle(){
        $this->title = new DbColumn();
    }
    public function getText(){
        return $this->text;
    }
    public function setText(){
        $this->text = new DbColumn();
    }
    public function getCategory(){
        return $this->category;
    }
    public function setCategory(){
        $this->category = new DbColumn();
    }
    public function getIcon(){
        return $this->icon;
    }
    public function setIcon(){
        $this->icon = new DbColumn();
    }
}
class TaxiArticlesModel extends DbDataModel{
    /**
     * @var TaxiArticlesColumns $columns_update
     * @var TaxiArticlesColumns $columns_where
     */
    public $columns_update;
    public $columns_where;

    public function InitDop()
    {
        $this->setTableName('taxi_articles');
        $this->setTableItemPrefix('article_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_update = new TaxiArticlesColumns();
        $this->columns_where = new TaxiArticlesColumns();
    }

}

class TaxiArticles extends DbData{
    /**
     * @var DbDataModel $model
     */
    public $model;
    /**
     * @var $model DbDataModel
     */
    public function CreateModel()
    {
        $this->model = new TaxiArticlesModel();
    }
}