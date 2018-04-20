<?php
/**
 * Created by PhpStorm.
 * User: Allison
 * Date: 2/14/2017
 * Time: 5:25 PM
 */

require("config.php");
require("includes/functions.php");
require("includes/api_access.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheduled Test Report</title>

    <link type="text/css" href="styles/styles.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>

</head>
<body>



<div id="container">

    <div class="header">
        <h2>NetBeez Scheduled Test Report</h2>

        <p>Select a time period and scheduled test and/or agent to generate a CSV report.</p>
        <p class="description"><strong>NOTE:  </strong>If you select a scheduled test and do not specify an agent, the results will be averages from all agents associated with the test.</p>
        <p class="description"><strong>NOTE:  </strong>If you select a scheduled test and specify an agent that is not associated with the test, you will get a blank CSV.  (The form is not yet smart enough to know which agents are associated with which tests.)</p>

    </div>

    <form class="form" action="includes/report_form_handler.php" method="post">

        <div class="form-section-row">

            <div class="form-item full">
                <label>From:</label>
                <input type="date" name="from" value="<?php if (isset($_POST['from'])) echo $_POST['from']; ?>" />
            </div>

            <div class="form-item full">
                <label>To:</label>
                <input type="date" name="to" value="<?php if (isset($_POST['to'])) echo $_POST['to']; ?>"/>
            </div>

            <div class="form-item full">
                <label>Select Scheduled Test:</label>
                <?php generate_sched_template_dropdown(); ?>
            </div>

            <div class="form-item full">
                <label>Select Agent:</label>
                <?php generate_agent_dropdown(); ?>
            </div>

            <div class="form-item full" id="test-type-select-element">
                <label>Select Scheduled Test Type:</label>
                <select name="test_type">
                    <option value="5">Iperf</option>
                    <option value="7">SpeedTest</option>
                    <option value="8">VoIP</option>
                </select>
            </div>
        </div>

        <div class="form-footer">
            <input class="button button-primary" type="submit" name="submit" value="Get Report">
        </div>


    </form>
</div>


<script>
    $('#test-template-id-select').change(function(){
        console.log("selection action");
        if($(this).val() != 0){
            $('#test-type-select-element').hide();
        } else {
            $('#test-type-select-element').show();
        }
    });

</script>
</body>
</html>
