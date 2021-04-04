<?php

class TaxiPartnersColumns extends DbDataColumns{
    private $id;
    private $uname;
    private $site;
    private $phone;
    private $coment;
    private $ptype;
    private $time;

    public function __construct()
    {
        $this->setId();
        $this->getId()->setName('id');
        $this->getId()->setType(TYPE_UINT);
        $this->setUname();
        $this->getUname()->setName('uname');
        $this->getUname()->setType(TYPE_STR);
        $this->setSite();
        $this->getSite()->setName('site');
        $this->getSite()->setType(TYPE_STR);
        $this->setPhone();
        $this->getPhone()->setName('phone');
        $this->getPhone()->setType(TYPE_STR);
        $this->setComent();
        $this->getComent()->setName('coment');
        $this->getComent()->setType(TYPE_STR);
        $this->setType();
        $this->getType()->setName('type');
        $this->getType()->setType(TYPE_STR);
        $this->setTime();
        $this->getTime()->setName('time');
        $this->getTime()->setType(TYPE_STR);
    }

    public function getId(){
        return $this->id;
    }
    public function setId(){
        $this->id = new DbColumn();
    }
    public function getUname(){
        return $this->uname;
    }
    public function setUname(){
        $this->uname = new DbColumn();
    }
    public function getSite(){
        return $this->site;
    }
    public function setSite(){
        $this->site = new DbColumn();
    }
    public function getPhone(){
        return $this->phone;
    }
    public function setPhone(){
        $this->phone = new DbColumn();
    }
    public function getComent(){
        return $this->coment;
    }
    public function setComent(){
        $this->coment = new DbColumn();
    }
    public function getType(){
        return $this->ptype;
    }
    public function setType(){
        $this->ptype = new DbColumn();
    }
    public function getTime(){
        return $this->time;
    }
    public function setTime(){
        $this->time = new DbColumn();
    }
}
class TaxiPartnersModel extends DbDataModel{
    public $columns_update;
    public $columns_where;

    public function InitDop()
    {
        $this->setTableName('taxi_partners');
        $this->setTableItemPrefix('partner_');
        $this->setTablePrimaryKey($this->GetTableItemNameSimple('id'));
        $this->columns_update = new TaxiPartnersColumns();
        $this->columns_where = new TaxiPartnersColumns();
    }
}

class TaxiPartners extends DbData{
    public $model;
    public function CreateModel()
    {
        $this->model = new TaxiPartnersModel();
    }
    public function getPartnerById($id){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().".*");
        $this->model->addWhereCustom("partner_id = ".$this->db->sql_prepare($id));
        return $this->GetItem();
    }
    public function getCount(){
        $this->CreateModel();
        return $this->GetTotal();
    }
    public function getPartners($count=20, $start=1){
        $this->CreateModel();
        $this->model->setSelectField($this->model->getTableName().'.*');
        $this->model->setStart($start);
        $this->model->setCount($count);
        return $this->GetList();
    }

    public function addPartner( $data,$update=0){

        $this->CreateModel();
        if ($data['uname']){
                $this->model->columns_update->getUname()->setValue($data['uname']);
        }
        if($data['site']){
            $this->model->columns_update->getSite()->setValue($data['site']);
        }
        if($data['phone']){
            $this->model->columns_update->getPhone()->setValue($data['phone']);
        }
        if($data['coment']){
            $this->model->columns_update->getComent()->setValue($data['coment']);
        }
        if($data['type']){
            $this->model->columns_update->getType()->setValue($data['type']);
        }
        if ($update){
            if ($data['id']){
                $this->model->columns_where->getId()->setValue($data['id']);
                $check = $this->GetItem();
            }
        }
        if ($update and $check){
            $this->model->columns_where->getId()->setValue($data['id']);
            $result = $this->Update();
            if ($result ) {
                $array['status'] = true;
                $array['text'] = 'Значение успешно обновлено';
            }
            else{
                $array['text'] = 'Ошибка обновления';
            }
        }
        else{
            $result = $this->Insert();
            if ($result) {
                $array['status'] = true;
            }
            else{
                $array['text'] = 'Ошибка обновления';
            }
        }
        return $array;
    }
}
