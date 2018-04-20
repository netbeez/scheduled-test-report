<?php
/**
 * Created by PhpStorm.
 * User: Allison
 * Date: 2/15/2017
 * Time: 10:49 AM
 */

require("functions.php");
require("api_access.php");
require("../config.php");


if (isset($_POST['submit'])){

    //process & format form input

    $from = $_POST['from'];
    $to = $_POST['to'];
    $test_template_id = $_POST['test_template_id'];
    $agent_id = $_POST['agent_id'];
    $test_type_id = $_POST['test_type'];

    $from_ts = new DateTime($from);
    $from_ts = $from_ts->getTimestamp() * 1000;

    $to_ts = new DateTime($to);
    $to_ts = $to_ts->getTimestamp() * 1000;

    $filename = 'NetBeez_Report_' . $from . '_-_' . $to . '_';

    $query_params = array();

    if ($test_template_id == 0 && $agent_id != 0){
        $query_params = array('agent_id' => $agent_id, 'test_type_id' => $test_type_id);
        $agent_name = preg_replace('/\s+/', '_', get_agent_name($agent_id));
        $filename = $filename . 'AGENT-' . $agent_name . '_-_';
    } else if ($test_template_id != 0 && $agent_id == 0){
        $query_params = array('scheduled_nb_test_template_id' => $test_template_id);
        $test_template_info = json_decode(Scheduled_Nb_Test_Templates::show($test_template_id));
        $test_type_id = $test_template_info->{'test_type_id'};
        $test_name = $test_template_info->{'label'};
        $test_name = preg_replace('/\s+/', '_', $test_name);
        $filename = $filename . 'TEST-' . $test_name . '_-_';
    } else if ($test_template_id != 0 && $agent_id != 0){
        $query_params = array('agent_id' => $agent_id, 'scheduled_nb_test_template_id' => $test_template_id);
        $test_template_info = json_decode(Scheduled_Nb_Test_Templates::show($test_template_id));
        $test_type_id = $test_template_info->{'test_type_id'};
        $test_name = $test_template_info->{'label'};
        $test_name = preg_replace('/\s+/', '_', $test_name);
        $agent_name = preg_replace('/\s+/', '_', get_agent_name($agent_id));
        $filename = $filename . 'TEST-' . $test_name . '_' . 'AGENT-' . $agent_name . '_-_';
    } else if ($test_template_id == 0 && $agent_id == 0){
        $query_params = array('test_type_id' => $test_type_id);
    } else {
        $query_params = array();
    }

    //grab the data via NetBeez API

    $sched_test_data = json_decode(Scheduled_Nb_Test_Results::index($from_ts, $to_ts, 0, $query_params));
    $sched_test_data = $sched_test_data->{'nb_test_results'};

    //establish array variables

    $sched_test_data_object = array();
    $labels = array();
    $test_type_label = "";

    //parse data in accordance to type of scheduled test

    if ($test_type_id == 5){

        $labels = array("Timestamp", "Bandwidth (mbps)", "Jitter (ms)", "Packet Loss (%)");
        $filename = $filename . 'Iperf';

        for($i = 0; $i < count($sched_test_data); $i++){

            if(!empty($sched_test_data)){
                $sched_results = $sched_test_data[$i]->{'result_values'};

                $bandwidth = round($sched_results[0]->{'value'}, 2);
                $jitter = round($sched_results[1]->{'value'}, 2);
                $packet_loss = round($sched_results[2]->{'value'}, 2);
            } else {
                $bandwidth = 0;
                $jitter = 0;
                $packet_loss = 0;
            }

            $test_run = array(
                "Timestamp" => date('m/d/Y H:i:s', $sched_test_data[$i]->{'ts'}/1000),
                "Bandwidth (mbps)" => $bandwidth,
                "Jitter (ms)" => $jitter,
                "Packet Loss (%)" => $packet_loss
            );

            array_push($sched_test_data_object, $test_run);
        }
    } else if ($test_type_id == 7){

        $labels = array("Timestamp", "Download (mbps)", "Upload (mbps)", "Latency (ms)");
        $filename = $filename . 'SpeedTest';

        for($i = 0; $i < count($sched_test_data); $i++){

            if(!empty($sched_test_data)){
                $sched_results = $sched_test_data[$i]->{'result_values'};

                $download_speed = round($sched_results[0]->{'value'}, 2);
                $latency = round($sched_results[1]->{'value'}, 2);
                $upload_speed = round($sched_results[2]->{'value'}, 2);
            } else {
                $download_speed = 0;
                $latency = 0;
                $upload_speed = 0;
            }

            $test_run = array(
                "Timestamp" => date('m/d/Y H:i:s', $sched_test_data[$i]->{'ts'}/1000),
                "Download (mbps)" => $download_speed,
                "Upload (mbps)" => $upload_speed,
                "Latency (ms)" => $latency
            );

            array_push($sched_test_data_object, $test_run);
        }
    } else if ($test_type_id == 8){

        $labels = array("Timestamp", "bps", "Jitter (ms)", "Latency (ms)", "MOS", "Packet Loss (%)", "Packets Lost");
        $filename = $filename . 'VoIP';

        for($i = 0; $i < count($sched_test_data); $i++){

            if(!empty($sched_test_data)){
                $sched_results = $sched_test_data[$i]->{'result_values'};

                $bps = round($sched_results[0]->{'value'}, 2);
                $jitter = round($sched_results[1]->{'value'}, 2);
                $latency = round($sched_results[2]->{'value'}, 2);
                $mos = round($sched_results[3]->{'value'}, 2);
                $packet_loss = round($sched_results[4]->{'value'}, 2);
                $packets_lost = round($sched_results[5]->{'value'}, 2);
            } else {
                $bps = 0;
                $jitter = 0;
                $latency = 0;
                $mos = 0;
                $packet_loss = 0;
                $packets_lost = 0;
            }

            $test_run = array(
                "Timestamp" => date('m/d/Y H:i:s', $sched_test_data[$i]->{'ts'}/1000),
                "bps" => $bps,
                "Jitter (ms)" => $jitter,
                "Latency (ms)" => $latency,
                "MOS" => $mos,
                "Packet Loss (%)" => $packet_loss,
                "Packets Lost" => $packets_lost
            );

            array_push($sched_test_data_object, $test_run);
        }
    }

    function sort_dates($a, $b)
    {
        $ts1 = strtotime($a['Timestamp']);
        $ts2 = strtotime($b['Timestamp']);

        return $ts1 - $ts2;
    }

    usort($sched_test_data_object, 'sort_dates');

    //generate the CSV file

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='. $filename . '.csv');

    $fp = fopen('php://output', 'w');

    fputcsv($fp, $labels);

    foreach ($sched_test_data_object as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
}