<?php
class TaxiOrdersColumns extends DbDataColumns
{
    private $id;
    private $data;
    private $time;
    private $checked;
    private $code;
    private $phone;
    private $confirm;
    private $status;
    private $user_id;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);
        $this->setData();
        $this->getData()->setName('data');
        $this->getData()->setType(TYPE_STR);
        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_UNIXTIME);
        $this->setChecked();
        $this->getChecked()->setName('checked');
        $this->getChecked()->setType(TYPE_BOOL);
        $this->setCode();
        $this->getCode()->setName('code');
        $this->getCode()->setType(TYPE_UINT);
        $this->setPhone();
        $this->getPhone()->setName('phone');
        $this->getPhone()->setType(TYPE_STR);
        $this->setConfirm();
        $this->getConfirm()->setName('confirm');
        $this->getConfirm()->setType(TYPE_UINT);
        $this->setStatus();
        $this->getStatus()->setName('status');
        $this->getStatus()->setType(TYPE_UINT);
        $this->setUserId();
        $this->getUserId()->setName('user_id');
        $this->getUserId()->setType(TYPE_UINT);
    }

    public function getId(){
        return $this->id;
    }
    public function setId(){
        $this->id = new DbColumn();
    }
    public function getData(){
        return $this->data;
    }
    public function setData(){
        $this->data = new DbColumn();
    }
    public function getTime(){
        return $this->time;
    }
    public function setTime(){
        $this->time = new DbColumn();
    }
    public function getChecked(){
        return $this->checked;
    }
    public function setChecked(){
        $this->checked =  new DbColumn();
    }
    public function getCode(){
        return $this->code;
    }
    public function setCode(){
        $this->code = new DbColumn();
    }
    public function getPhone(){
        return $this->phone;
    }
    public function setPhone(){
        $this->phone = new DbColumn();
    }
    public function getConfirm(){
        return $this->confirm;
    }
    public function setConfirm(){
        $this->confirm = new DbColumn();
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus(){
        $this->status = new DbColumn();
    }
    public function getUserId(){
        return $this->user_id;
    }
    public function setUserId(){
        $this->user_id = new DbColumn();
    }
}

class TaxiOrdersModel extends DbDataModel{
    public $columns_update;
    public $columns_where;

    public function InitDop()
    {
        $this->setTableName('taxi_orders');
        $this->setTableItemPrefix('order_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_update = new TaxiOrdersColumns();
        $this->columns_where = new TaxiOrdersColumns();
    }
}

class TaxiOrders extends DbData{
    public $model;

    public function CreateModel(){
        $this->model = new TaxiOrdersModel();
    }

    public function GetItemById ($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*, taxi_orders_statuses.*");
        $this->model->setJoin("LEFT JOIN taxi_orders_statuses ON taxi_orders.order_status=taxi_orders_statuses.status_id");
        $this->model->columns_where->getId()->setValue($id);
        return $this->GetItem();
    }
    public function DisableItemById($id){
        $this->CreateModel();
        $this->model->columns_where->getId()->setValue($id);
        $this->model->columns_update->getStatus()->setValue(0);
        return $this->Update();
    }
    public function SetOrderChecked ($id){
        $this->CreateModel();
        $this->model->columns_where->getId()->setValue($id);
        $this->model->columns_update->getChecked()->setValue('1');
        return $this->Update();
    }
    public function ChangePriceTelegram ($id, $price){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->columns_where->getId()->setValue($id);
        $item = $this->GetItem();
        $item['order_data'] = unserialize($item['order_data']);
        $item['order_data']['price'] = $price;
        $item['order_data'] = serialize($item['order_data']);
        $this->model->columns_update->getData()->setValue($item['order_data']);
        return $this->Update();
    }
    public function ChangeStatusTelegram ($id, $status){
        $this->CreateModel();
        $this->model->columns_where->getId()->setValue($id);
        $this->model->columns_update->getStatus()->setValue($status);
        return $this->Update();
    }
    public function GetLastOrdersTelegram(){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setOrderBy('order_id DESC');
        $this->model->setCount(10);
        return $this->GetList();

    }
    public function GetSubmitedByUser ($user_id)
    {
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->columns_where->getUserId()->setValue($user_id);
        $this->model->columns_where->getStatus()->setValue('4');
        return $this->GetTotal();

    }

	public function GetItemsByUser ($user_id){
		$this->CreateModel();
		$this->model->setSelectField($this->model->getTableName().".*, taxi_orders_statuses.*");
		$this->model->setJoin("LEFT JOIN taxi_orders_statuses ON taxi_orders.order_status=taxi_orders_statuses.status_id");
		$this->model->columns_where->getUserId()->setValue($user_id);
		$this->model->setOrderBy('order_time DESC');
		$this->model->setCount(3);
		return $this->GetList();
	}

    public function GetItemsByPhone ($phone){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*, taxi_orders_statuses.*");
        $this->model->setJoin("LEFT JOIN taxi_orders_statuses ON taxi_orders.order_status=taxi_orders_statuses.status_id");
        $this->model->columns_where->getPhone()->setValue($phone);
        $this->model->setOrderBy('order_time DESC');
        $this->model->setCount(3);
        return $this->GetList();
    }

    public function GetAllById ($id, $status=0, $start=0, $count=0){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*, taxi_orders_statuses.*");
        $this->model->columns_where->getUserId()->setValue($id);
        $this->model->setJoin("LEFT JOIN taxi_orders_statuses ON taxi_orders.order_status=taxi_orders_statuses.status_id");
        if ($status){
            $this->model->columns_where->getStatus()->setValue($status);
        }
        $this->model->setOrderBy('order_time DESC');
        if ($count){
            $this->model->setStart($start);
            $this->model->setCount($count);
        }

        return $this->GetList();
    }
    public function GetTotalById($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->columns_where->getUserId()->setValue($id);
        $this->model->columns_where->getStatus()->setValue(4);
        $this->model->setOrderBy('order_time DESC');

        return $this->GetTotal();
    }

    public function getCount(){
        $this->CreateModel();
        return $this->GetTotal();

    }

    public function getOrders($count=20, $start=1){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setOrderBy("order_time DESC");
        $this->model->setStart($start);
        $this->model->setCount($count);
        return $this->GetList();
    }
    public function getLastSubmitedById($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->setOrderBy("order_time DESC");
        $this->model->columns_where->getUserId()->setValue($id);
        $this->model->columns_where->getStatus()->setValue('4');
        return $this->GetItem();


    }
}
