<?php

class TaxiPricesColumns extends DbDataColumns {

    private $id;
    private $route_id;
    private $class_id;
    private $value;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);

        $this->setRouteId();
        $this->getRouteId()->setName('route_id');
        $this->getRouteId()->setType(TYPE_UINT);

        $this->setClassId();
        $this->getClassId()->setName('class_id');
        $this->getClassId()->setType(TYPE_UINT);

        $this->setValue();
        $this->getValue()->setName('value');
        $this->getValue()->setType(TYPE_UINT);
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
    public function getRouteId()
    {
        return $this->route_id;
    }

    private function setRouteId()
    {
        $this->route_id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getClassId()
    {
        return $this->class_id;
    }

    private function setClassId()
    {
        $this->class_id = new DbColumn();
    }

    /**
     * @return DbColumn
     */
    public function getValue()
    {
        return $this->value;
    }

    private function setValue()
    {
        $this->value = new DbColumn();
    }
}


class TaxiPricesModel extends DbDataModel {

    /**
     * @var  TaxiPricesColumns $columns_where
     */
    public $columns_where;
    /**
     * @var  TaxiPricesColumns $columns_update
     */
    public $columns_update;


    public function InitDop () {
        $this->setTableName('`taxi_prices`');
        $this->setTableItemPrefix('price_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_where=new TaxiPricesColumns();
        $this->columns_update=new TaxiPricesColumns();
    }
}

class TaxiPrices extends  DbData
{

    /**
     * @var  TaxiPricesModel $model
     */
    public $model;

    /**
     * @var $model TaxiPricesModel
     */
    public function CreateModel () {
        $this->model=new TaxiPricesModel();
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


    public function PrepareData ($result_item,$full=0) {

        $result_item=$this->registry->files->FilePrepare($result_item,'icon_');
        $result_item['city_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon_');

        $result_item=$this->registry->files->FilePrepare($result_item,'icon2_');
        $result_item['class_icon_url'] = $this->registry->files->GetImageUrl($result_item,'medium',0,'icon2_');


        $result_item['route_full_url']=BASE_URL.'/prices/'.$result_item['from_city_url'].'/'.$result_item['to_city_url'].'/';
        return $result_item;
    }

    public function getPriceOnClass ($from = 113, $to=0, $class=1){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".price_value");
        $this->model->setJoin("INNER JOIN taxi_routes ON taxi_routes.route_start=$from
                                    AND route_end=$to AND taxi_prices.price_route_id=taxi_routes.route_id");
        $this->model->addWhereCustom("taxi_prices.price_class_id=".$this->db->sql_prepare($class));
        return $this->GetItem();
    }

    public function getMinPrice ($from = 113, $to=0){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".price_value");
        $this->model->setJoin("INNER JOIN taxi_routes ON taxi_routes.route_start=".$this->db->sql_prepare($from)." 
                                    AND route_end=".$this->db->sql_prepare($to)." AND taxi_prices.price_route_id=taxi_routes.route_id");
        $this->model->addWhereCustom("taxi_prices.price_class_id=2");
        return $this->GetItem();
    }

    public function getPricesNew( $from, $to=0,$pop=false, $km=0){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setJoin("INNER JOIN taxi_routes ON taxi_prices.price_route_id = taxi_routes.route_id
                    INNER JOIN taxi_cities cities_from ON taxi_routes.route_start=cities_from.city_id
                    INNER JOIN taxi_cities cities_to ON taxi_routes.route_end=cities_to.city_id
                    INNER JOIN taxi_classes ON taxi_prices.price_class_id=taxi_classes.class_id");
        $this->model->addWhereCustom("taxi_routes.route_status=1");
        $this->model->addWhereCustom("taxi_prices.price_value > 0 ");
        $this->model->addWhereCustom("cities_from.city_status=1 ");
        $this->model->addWhereCustom("cities_to.city_status=1");
        $this->model->addWhereCustom("taxi_routes.route_start=".$this->db->sql_prepare($from));

        if ($to) {
            $this->model->addWhereCustom('taxi_routes.route_end='.$this->db->sql_prepare($to));
        }
        if ($pop) {
            $this->model->addWhereCustom('cities_to.city_icon!=0');
            $this->model->setOrderBy('route_views');
            $this->model->setOrderWay('DESC');
        }
        $data = $this->GetList();

        $array=array();
//
        if ($pop) {
            foreach($data as $item){
                if ($item['price_value']) {
                    $array[$item['route_end']]['classes'][$item['price_class_id']]=$item;
                    if (isset($array[$item['route_end']]['info'])==false or
                        $array[$item['route_end']]['info']['price']>$item['price_value'] or $km) {

                        $array[$item['route_end']]['info']=array(
                            'img'=>$item['city_icon_url'],
                            'title'=>$item['to_city_title'],
                            'price'=>$item['price_value'],
                            'link'=>$item['route_full_url']
                        );
                    }

                }

            }
        }
        else{
            foreach($data as $item){
                if ($item['price_value']) {
                    $array[$item['route_start']][$item['route_end']]['url']=$item['route_full_url'];
                    $array[$item['route_start']][$item['route_end']]['classes'][$item['price_class_id']]=$item;

                }

            }
        }

        return $array;
    }


    public function getPricesFrom($from_id, $to_id=0,$pop=false,$km=0) {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*, taxi_routes.*,taxi_classes.*,
        cities_from.city_title as from_city_title, cities_from.city_url as from_city_url,
         cities_to.city_title as to_city_title, cities_to.city_url as to_city_url');
        $this->model->setJoin('INNER JOIN taxi_routes ON taxi_prices.price_route_id=taxi_routes.route_id
        INNER JOIN taxi_cities cities_from ON taxi_routes.route_start=cities_from.city_id
        INNER JOIN taxi_cities cities_to ON taxi_routes.route_end=cities_to.city_id
        INNER JOIN taxi_classes ON taxi_prices.price_class_id=taxi_classes.class_id        
        ');
        $this->model->SetJoinImage('icon','cities_to.city_icon');
        $this->model->SetJoinImage('icon2','taxi_classes.class_icon');

        $this->model->addWhereCustom('taxi_routes.route_status=1');
        $this->model->addWhereCustom('taxi_prices.price_value > 0');
        $this->model->addWhereCustom('cities_from.city_status=1');
        $this->model->addWhereCustom('cities_to.city_status=1');
        $this->model->addWhereCustom('taxi_routes.route_start='.$this->db->sql_prepare($from_id));
        $this->model->addWhereCustom('taxi_routes.route_end!='.$this->db->sql_prepare($from_id));

        if ($to_id) {
            $this->model->addWhereCustom('taxi_routes.route_end='.$this->db->sql_prepare($to_id));
        }
//        if ($pop) {
//            $this->model->addWhereCustom('cities_to.city_icon!=0');
//            $this->model->setOrderBy('route_views');
//            $this->model->setOrderWay('DESC');
//        }
        $data=$this->GetList();

            $array = array();


        if ($pop) {
            foreach($data as $item){
                if ($item['price_value']) {
                    $array[$item['route_end']]['classes'][$item['price_class_id']]=$item;
                    if (isset($array[$item['route_end']]['info'])==false or
                        $array[$item['route_end']]['info']['price']>$item['price_value'] or $km) {

                        $array[$item['route_end']]['info']=array(
                            'img'=>$item['city_icon_url'],
                            'title'=>$item['to_city_title'],
                            'price'=>$item['price_value'],
                            'link'=>$item['route_full_url']
                        );
                    }

                }

            }
        }
        else {
            foreach($data as $item){
                if ($item['price_value']) {
                    $array[$item['route_start']][$item['route_end']]['url']=$item['route_full_url'];
                    $array[$item['route_start']][$item['route_end']]['classes'][$item['price_class_id']]=$item;
                }

            }
        }
       // $array[] = $this->getPricesRevers($from_id);
        return $array;
    }

}
