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
            //TODO: FIX repeated spanning issue here:
            $rightStr .= str_repeat('</span>', $rightCount);
            $drug = $leftStr.$centerStr.$rightStr;
            
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
        $position_range_trial_one = array(4,5,6,7,8,9,10,11,12,13,14,15);
        $position_range_trial_two = array(30,31,32,33,34,35,36,37,38,39,40,41);
        $position_range_trial_three = array(56,57,58,59,60,61,62,63,64,65,66,67);
        $position_range_trial_four = array(82,83,84,85,86,87,88,89,90,91,92,93);
        $total_num_saves_trial_one = 0;
        $total_num_saves_trial_two = 0;
        $total_num_saves_trial_three = 0;
        $total_num_saves_trial_four = 0;
        echo "<div class='savedItemsArea'>";
        echo "<ol>";
        echo "<h2 style='padding-top:10px;margin-bottom:8px;'><strong>Saved Items:</strong></h2>";
        if($_SESSION['Position'] >= 17 && $_SESSION['Position'] <= 28) {
            if($_SESSION['Position'] == 17) {
                foreach($position_range_trial_one as $key => $value) {
                    
                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        $total_num_saves_trial_one++;
                    }
                }
            } else {
                foreach($position_range_trial_one as $key => $value) {

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        if($_SESSION['Trials'][$_SESSION['Position']-1]['Response']["itemcheckbox" . $static_interaction] == "CHECKED") {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED' checked><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        } else {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        }
                        $total_num_saves_trial_one++;
                    }
                }
            } 
            echo "<p><strong>Total Number of Items Saved: " . $total_num_saves_trial_one . "</strong></p>";
        } elseif($_SESSION['Position'] >= 43 && $_SESSION['Position'] <= 54) {
            if($_SESSION['Position'] == 43) {
                foreach($position_range_trial_two as $key => $value) {

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        $total_num_saves_trial_two++;
                    }
                }
            } else {
                foreach($position_range_trial_two as $key => $value) {

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        if($_SESSION['Trials'][$_SESSION['Position']-1]['Response']["itemcheckbox" . $static_interaction] == "CHECKED") {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED' checked><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        } else {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        }
                        $total_num_saves_trial_two++;
                    }
                }
            } 
            echo "<p><strong>Total Number of Items Saved: " . $total_num_saves_trial_two . "</strong></p>";

        } elseif($_SESSION['Position'] >= 69 && $_SESSION['Position'] <= 80) {
            if($_SESSION['Position'] == 69) {
                foreach($position_range_trial_three as $key => $value) {

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        $total_num_saves_trial_three++;
                    }
                }
            } else {
                foreach($position_range_trial_three as $key => $value) {
                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {

                        $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                        if(preg_match('/\s/', $static_interaction)) {
                            $static_interaction = str_replace(' ', '_', $static_interaction);
                        }

                        if($_SESSION['Trials'][$_SESSION['Position']-1]['Response']["itemcheckbox" . $static_interaction] == "CHECKED") {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED' checked><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        } else {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        }
                        $total_num_saves_trial_three++;
                    }
                }
            } 

            echo "<p><strong>Total Number of Items Saved: " . $total_num_saves_trial_three . "</strong></p>";

        } elseif($_SESSION['Position'] >= 95 && $_SESSION['Position'] <= 106) {
            if($_SESSION['Position'] == 95) {
                foreach($position_range_trial_four as $key => $value) {

                    $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                    if(preg_match('/\s/', $static_interaction)) {
                        $static_interaction = str_replace(' ', '_', $static_interaction);
                    }

                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {
                        echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        $total_num_saves_trial_four++;
                    }
                }
            } else {
                foreach($position_range_trial_four as $key => $value) {
                    if($_SESSION['Trials'][$value]['Response']['numsavestatus'] == "SAVE") {

                        $static_interaction = $_SESSION['Trials'][$value]['Response']['interactionstatic'];
                        if(preg_match('/\s/', $static_interaction)) {
                            $static_interaction = str_replace(' ', '_', $static_interaction);
                        }

                        if($_SESSION['Trials'][$_SESSION['Position']-1]['Response']["itemcheckbox" . $static_interaction] == "CHECKED") {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED' checked><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        } else {
                            echo "<li><input type='checkbox' name='itemcheckbox" . $static_interaction . "' value='CHECKED'><del>" . $_SESSION['Trials'][$value]['Response']['drugonestatic'] . " + " . $_SESSION['Trials'][$value]['Response']['drugtwostatic'] . " = " . $_SESSION['Trials'][$value]['Response']['interactionstatic'] . "</del></h1>";
                        }
                        $total_num_saves_trial_four++;
                    }
                }
            } 
            
            echo "<p><strong>Total Number of Items Saved: " . $total_num_saves_trial_four . "</strong></p>";
        }
        
        
        echo "</ol>";
        echo "</div>";

        // $numSaves = $_SESSION['Trials'][$_SESSION['Position']-1]['Response']['numsavestatus'];
    ?>
    
    <div><?php echo $text; ?></div>

    <div class="imageArea">
        <?= $drugs[0] ?>
        <div class="divider">+</div>
        <?= $drugs[1] ?>
        <div class="divider">=</div>
        <div class="sideEffect">
            <select name="Response">
                <option disabled hidden selected></option>
                <option>dry mouth</option>
                <option>itching</option>
                <option>cough</option>
                <option>trembling</option>
                <option>flushing</option>
                <option>fever</option>
                <option>fatigue</option>
                <option>bloating</option>
                <option>clumsiness</option>
                <option>nausea</option>
                <option>diarrhea</option>
                <option>arm pain</option>
            </select>
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
