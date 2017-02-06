<?php

include 'Admin.php';

class schedule extends admin {

    function __construct() {
        parent::__construct();
        parent::checktoken();
        $this->load->model('v1/admin/schedule_model');
    }

    public function get_course() {
        echo json_encode($this->_get());
    }

    public function check_room() {
        echo json_encode($this->_check_room());
    }

    public function add_room() {
        echo json_encode($this->_add_room());
    }

//Custom Function   
//PRIVATE FUNCTION

    private function _all() {
        try {
            $get_all_day = $this->day_model->all();
            if ($get_all_day['response'] == OK_STATUS) {
                $data = get_success($get_all_day['data']);
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
                $addbuilding = $this->day_model->add($params);
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
                $del = $this->day_model->delete($seq);
                if ($del['response'] == OK_STATUS) {
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
            $course_seq = $this->uri->segment(5);
            if ($course_seq != "") {
                $days = $this->schedule_model->get_days();
                $building = $this->schedule_model->get_building($course_seq);
                $building_seq = $building['data']->building_seq;
                $get_rooms = $this->schedule_model->get_room($building_seq);
                $rooms = $get_rooms['data'];
                if ($days['response'] == OK_STATUS) {
                    foreach ($days['data'] as $day) {
                        $day_hour = $this->schedule_model->get_day_hour($day->seq, $rooms);
                        $day_hours_data[] = array("day_seq" => $day->seq, "day_name" => $day->name, "hour" => $day_hour['data']);
                    }
                    $res = new stdClass();
                    $res->day_hours = $day_hours_data;
                    $res->rooms = $rooms;
                    $data = get_success($res);
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
                $params->seq = $seq;
                $put = $this->day_model->put($params);
                if ($put['response'] == OK_STATUS) {
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

//    private function _check_rooms() {
//        try {
//            $datas = json_decode(file_get_contents('php://input'));
//            $day_hour_seq = $datas->pick_dh_seq;
//            $rooms_data = $datas->rooms;
//            if ($day_hour_seq != "" AND $rooms_data) {
//                foreach ($rooms_data as $each) {
//                    $check_room_availability = $this->schedule_model->check_room_availability($day_hour_seq, $each->seq);
//                    $check_result = $check_room_availability['data'];
//                    $result[] = array("room_seq" => $each->seq, "room_name" => $each->name, "room_availability" => $check_result);
//                }
//                $results = $result;
//                $data = get_success($results);
//            } else {
//                $data = response_fail();
//            }
//        } catch (Exception $e) {
//            $data = response_fail();
//        }
//        return $data;
//    }

    private function _add_room() {
        try {
            $datas = json_decode(file_get_contents('php://input'));
            $class_seq = $datas->class_seq;
            if ($datas != "") {
                foreach ($datas->room_data as $data) {
                    if ($data->availability == 'YES') {
                        $add_room = $this->schedule_model->add_room($class_seq, $data);
                    }
                }
                $data = response_success();
            } else {
                $data = response_fail();
            }
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

    private function _check_room() {
        try {
            $datas = json_decode(file_get_contents('php://input'));
            $day_hour_seq = $datas->pick_dh_seq;
            $room_seq = $datas->pick_room_seq;
            $check_room_availability = $this->schedule_model->check_room_availability($day_hour_seq, $room_seq);
            $check_result = $check_room_availability['data'];
            $result = array("room_seq" => $room_seq, "room_availability" => $check_result);
            $data = get_success($result);
        } catch (Exception $e) {
            $data = response_fail();
        }
        return $data;
    }

}