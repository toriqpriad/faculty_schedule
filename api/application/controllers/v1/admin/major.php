<?php

include 'admin.php';

class room extends admin {

    function __construct() {
        parent::__construct();
        parent::checktoken();
    }

    public function all() {
        echo json_encode($this->_all());
    }

    public function add() {
        echo json_encode($this->_add());
    }

    public function delete() {
        echo json_encode($this->_delete());
    }

    public function get() {
        echo json_encode($this->_get());
    }

    public function put() {
        echo json_encode($this->_put());
    }

//Custom Function
    private function _get_building_option() {
        $get_building = $this->room_model->get_building_option();
        return $get_building;
    }

//PRIVATE FUNCTION

    private function _all() {
        try {
            $get_all_room = $this->room_model->all();
            $get_building_option = $this->_get_building_option();
            if ($get_all_room ['response'] == OK_STATUS) {
                $data = array("rooms" => $get_all_room['data'], "building_option" => $get_building_option['data']);
                $data = get_success($data);
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

    private function _add() {
        try {
            $datas = json_decode(file_get_contents('php://input'));
            if ($datas != "") {
                $params = new stdClass();
                $params->name = $datas->name;
                $params->building_seq = $datas->building_seq;
                $params->description = $datas->description;
                $addbuilding = $this->room_model->add($params);
                if ($addbuilding['response'] == OK_STATUS) {
                    $data = response_success();
                } else {
                    $data = response_fail();
                }
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

    private function _delete() {
        try {
            $seq = $this->uri->segment(5);
            if ($seq != "") {
                $addbuilding = $this->room_model->delete($seq);
                if ($addbuilding['response'] == OK_STATUS) {
                    $data = response_success();
                } else {
                    $data = response_fail();
                }
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

    private function _get() {
        try {
            $seq = $this->uri->segment(5);
            if ($seq != "") {
                $getbuilding = $this->room_model->get($seq);
                if ($getbuilding['response'] == OK_STATUS) {
                    $data = get_success($getbuilding['data']);
                } else {
                    $data = response_fail();
                }
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

    private function _put() {
        try {

            $datas = json_decode(file_get_contents('php://input'));
            $seq = $this->uri->segment(5);
            if ($datas != "" AND $seq != "") {
                $params = new stdClass();
                $params->name = $datas->name;
                $params->description = $datas->description;
                $params->building_seq = $datas->building_seq;
                $params->seq = $seq;
                $putbuilding = $this->room_model->put($params);
                if ($putbuilding['response'] == OK_STATUS) {
                    $data = response_success();
                } else {
                    $data = response_fail();
                }
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

}