<?php
    $compTime = 5;        // time in seconds to use for 'computer' timing
    
    $imageFilePath = dirname($_SESSION['Trial Types'][$trialType]['trial']) . '/bottle.jpg';
    $answers = explode('|', $answer);
    $cues = explode('|', $cue);
    
    if (isset($_SESSION['Drugs'][$cue])) {
        $drugs = $_SESSION['Drugs'][$cue];
    } else {
        $drugs = explode('|', $cue);
        shuffle($drugs);
        $_SESSION['Drugs'][$cue] = $drugs;
    }
    
    foreach ($drugs as &$drug) {
        if (show($drug) !== $drug) {
            $thisAnswer = '';
            foreach ($answers as $i => $answer) {
                if ($cues[$i] === $drug) {
                    $thisAnswer = $answer;
                }
            }
            $drug = '<div class="foodArea"> ' . show($drug) . '<div class="foodName">' . $thisAnswer . '</div></div>';
        } else {
            $len = strlen($drug);
            $str = '';
            $half = $len/2;
            $mid = floor($half);
            $leftStr = '';
            $rightStr = '';
            $centerStr = '';
            $rightCount = 0;
            
            for ($i=0; $i<$len; ++$i) {
                if ($i<$mid) {
                    $leftStr = '<span class="leftSkew">'.$leftStr.$drug[$i].'</span>';
                } elseif ($i+.5==$half) {
                    $centerStr = '<span class="centerSkew">'.$drug[$i].'</span>';
                } else {
                    ++$rightCount;
                    $rightStr = $rightStr.'<span class="rightSkew">'.$drug[$i];
                }
            }

            array_push($drugs_both_static, $drug);
            
            $drug = '<div class="bottleArea"> <div class="drugName">' . $drug . '</div> <img src="' . $imageFilePath . '"> </div>';
        }
    }

?>
    <style>
        .imageArea      {   white-space: nowrap;   }
        .imageArea img  {   height: 350px;  width: 200px;  }
        .imageArea > div    {   display: inline-block;  }
        .bottleArea     {   width: 200px; position: relative; }
        .drugName       {   position: absolute; top: 140px; left: 0px; width: 100%;   text-align: center;   font-family: Arial; font-size: 200%; }
        .drugName span  {   display: inline-block;  }
        .divider        {   font-size: 300%;    vertical-align: top;    margin: 120px 40px;  }
        .sideEffect     {   font-size: 200%;    vertical-align: top;    margin-top: 132px;  text-align: center;  }
        .sideEffect select    {   font-size: 80%; }
        

        
        #FormSubmitButton:disabled  {   border: 1px solid #eaeaea;  }
        .foodArea img   {   height: initial; width: initial;    max-width: 350px;   max-height: 350px;  }
        .foodName   {   margin-top: 15px;   text-align: center; font-size: 120%; }

        ol { list-style-type: none; font-size: 1.2em; font-style: italic; font-weight: bold;}
        ol li del { text-decoration: none; }
        ol li input[type=checkbox]:checked ~ del { text-decoration: line-through; }

        .savedItemsArea {
            background: url(http://i.stack.imgur.com/ynxjD.png) repeat-y;
            width: 600px;
            height: 375px;
            font: normal 14px verdana;
            line-height: 25px;
            padding: 2px 10px;
            border: solid 1px #ddd;
            margin-bottom: 35px;
        }
    </style>
    
    <?php
        $position_range_trial_one = array(9,10,11,12);
        $total_num_saves_trial_one = 0;
        $total_num_saves_trial_two = 0;
        $total_num_saves_trial_three = 0;
        $total_num_saves_trial_four = 0;
        echo "<div class='savedItemsArea'>";
        echo "<ol>";
        echo "<h2 style='padding-top:10px;margin-bottom:8px;'><strong>Saved Items:</strong></h2>";
        $drug_one_list = array();
        if($_SESSION['Position'] >= 14 && $_SESSION['Position'] <= 17) {
            if($_SESSION['Position'] == 14) {
                foreach($position_range_trial_one as $key => $value) {

                    array_push($drug_one_list, $_SESSION['Trials'][$value]['Response']['drugonestatic']);
                    shuffle($drug_one_list);

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonesavestatus'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwosavestatus'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionsavestatus'] . "</del></h1>";
                        $total_num_saves_trial_one++;
                    }
                }
            } else {
                foreach($position_range_trial_one as $key => $value) {

                    array_push($drug_one_list, $_SESSION['Trials'][$value]['Response']['drugonestatic']);
                    shuffle($drug_one_list);

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        if($_SESSION['Trials'][$_SESSION['Position']-1]['Response']["itemcheckbox" . $static_interaction] == "CHECKED") {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED' checked><del>" . $_SESSION['Trials'][$value]['Response']['drugonesavestatus'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwosavestatus'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionsavestatus'] . "</del></h1>";
                        } else {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonesavestatus'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwosavestatus'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionsavestatus'] . "</del></h1>";
                        }
                        $total_num_saves_trial_one++;
                    }
                }
            } 
            echo "<p><strong>Total Number of Items Saved: " . $total_num_saves_trial_one . "</strong></p>";
        }
        
        
        echo "</ol>";
        echo "</div>";

        // $numSaves = $_SESSION['Trials'][$_SESSION['Position']-1]['Response']['numsavestatus'];
    ?>
    
    <div><?php echo $text; ?></div>

    <div class="imageArea">
        <!-- Start Drug 1 Chunk -->
        <div class="bottleArea">
            <div class="drugName">
                <select name="Response">
                    <option disabled hidden selected></option>
                    <?php
                        for($ii = 0; $ii < count($drug_one_list); $ii++) {
                            echo "<option>" . $drug_one_list[$ii] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <img src="../Experiment/TrialTypes/Test_medone/bottle.jpg">
        </div>
        <!-- End Drug 1 Chunk -->
        <div class="divider">+</div>
        <?= $drugs[1] ?>
        <div class="divider">=</div>
        <div class="sideEffect">
            <?php
                echo $severity;
                if ($interaction !== '') {
                    // echo '<br><span>('.$interaction.')</span>';
                    echo $interaction;
                }
            ?>
        </div>
    </div>

    <div class="precache textright">
        <input class="button button-trial-advance" id="FormSubmitButton" type="submit" value="Submit" disabled />
    </div>


    <script>
        $("select[name='Response']").on("change", function() {
            $("#FormSubmitButton").prop("disabled", false);
        });
    </script>
