<?php
class TaxiArticlesCategoriesColumns extends DbDataColumns{
    private $id;
    private $title;
    private $alias;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setTitle();
        $this->getTitle()->setName('title');
        $this->getTitle()->setType(TYPE_STR);

        $this->setAlias();
        $this->getAlias()->setName('alias');
        $this->getAlias()->setType(TYPE_STR);



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
    public function getAlias(){
        return $this->alias;
    }
    public function setAlias(){
        $this->alias = new DbColumn();
    }
}
class TaxiArticlesCategoriesModel extends DbDataModel{
    /**
     * @var TaxiArticlesCategoriesColumns $columns_update
     * @var TaxiArticlesCategoriesColumns $columns_where
     */
    public $columns_update;
    public $columns_where;

    public function InitDop()
    {
        $this->setTableName('taxi_articles_categories');
        $this->setTableItemPrefix('art_cat_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_update = new TaxiArticlesCategoriesColumns();
        $this->columns_where = new TaxiArticlesCategoriesColumns();
    }

}

class TaxiArticlesCategories extends DbData{
    /**
     * @var TaxiArticlesCategoriesModel $model
     */
    public $model;
    /**
     * @var $model TaxiArticlesCategoriesModel
     */
    public function CreateModel()
    {
        $this->model = new TaxiArticlesCategoriesModel();
    }

    public function GetItemById ($id,$full=0){
        $this->CreateModel();
        $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem();
    }
    public function getCategoriesWichHasArticles(){
        $this->CreateModel();
        $this->model->setSelectField("DISTINCT ".$this->model->getTableName().".*");
        $this->model->setJoin("Inner JOIN core_content");
        $this->model->addWhereCustom("taxi_articles_categories.art_cat_alias=core_content.content_type");
        $this->model->setOrderBy('art_cat_id');

        return $this->GetList();

    }
}