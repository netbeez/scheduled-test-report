<?php
/**
 * Created by PhpStorm.
 * User: Allison
 * Date: 2/15/2017
 * Time: 12:15 PM
 */

function generate_agent_dropdown(){
    $agents = json_decode(Agents::index());
    $agents = $agents->{'agents'};

    echo '<select id="agent-id-select" name="agent_id">';
    echo '<option value="0">(do not specify)</option>';

    foreach ($agents as $agent){
        echo '<option value='. $agent->{'id'} . '>'. $agent->{'name'} . '</option>';
    }

    echo '</select>';
}

function generate_sched_template_dropdown(){
    $templates = json_decode(Scheduled_Nb_Test_Templates::index());

    echo '<select id="test-template-id-select" name="test_template_id">';
    echo '<option value="0">(do not specify)</option>';

    foreach ($templates as $template){
        echo '<option value='. $template->{'id'} . '>'. $template->{'label'} . '</option>';
    }

    echo '</select>';
}

function get_agent_name($id){
    $agent_obj = json_decode(Agents::show($id));
    $agent_name = $agent_obj->{'name'};

    return $agent_name;
}