<?php
    $compTime = 5;        // time in seconds to use for 'computer' timing

    $startingcounter = 2; // number to start for available saves per block
    $totalNumItems = 4;  // 12 trials per block

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

    echo "<div style='border: 3px solid black; width: 50%' padding-top: 10px; padding-bottom: 10px;>";

    if($_SESSION['Position'] == 14) {
        $numSaves = $startingcounter;
        $numItems = $totalNumItems;
        echo "<h4><strong>&nbsp;Trials remaining: " . $numItems . "</strong></h4>";
        echo "<h3><strong>&nbsp;Number of saves remaining: " . $numSaves . "</strong></h3>";
    } 
    // elseif($_SESSION['Position'] == 14) {
    //     $numSaves = $startingcounter;
    //     $numItems = $totalNumItems;
    //     echo "<h4><strong>&nbsp;Trials remaining: " . $numItems . "</strong></h4>";
    //     echo "<h3><strong>&nbsp;Number of saves remaining: " . $numSaves . "</strong></h3>";
    // } //elseif($_SESSION['Position'] == 56) {
    //     $numSaves = $startingcounter;
    //     $numItems = $totalNumItems;
    //     echo "<h4><strong>&nbsp;Trials remaining: " . $numItems . "</strong></h4>";
    //     echo "<h3><strong>&nbsp;Number of saves remaining: " . $numSaves . "</strong></h3>";
    //  } elseif($_SESSION['Position'] == 82) {
    //     $numSaves = $startingcounter;
    //     $numItems = $totalNumItems;
    //     echo "<h4><strong>&nbsp;Trials remaining: " . $numItems . "</strong></h4>";
    //     echo "<h3><strong>&nbsp;Number of saves remaining: " . $numSaves . "</strong></h3>";
    // } 
    else {
        $numSaves = $_SESSION['Trials'][$_SESSION['Position']-1]['Response']['newnumsavesvalue'];
        $numItems = $_SESSION['Trials'][$_SESSION['Position']-1]['Response']['numitemsremval'];
        echo "<h4><strong>&nbsp;Trials remaining: " . $numItems . "</strong></h4>";
        echo "<h3><strong>&nbsp;Number of saves remaining: " . $numSaves . "</strong></h3>";
    }

    echo "</div><br /><br />";

    $drugs_both_static = array();
    $interaction_static = $_SESSION['Trials'][$_SESSION['Position']]['Procedure']['Severity'];
    
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
        .sideEffect span    {   font-size: 80%; }
        .foodArea img   {   height: initial; width: initial;    max-width: 350px;   max-height: 350px;  }
        
        .oldNewAnsArea { margin: 20px; text-align: center; }
        .oldNewAnsArea button { width: 100px; padding: 12px; margin: 0px 20px; text-align: center; font-weight: bold; font-size: 1em;}
        .selectedChoice { border: 2px solid green !important; }
        .foodName   {   margin-top: 15px;   text-align: center; font-size: 120%; }

        .drugOneSaveArea { margin: 20px; text-align: center; }
        .drugOneSaveArea button { width: 100px; padding: 12px; text-align: center; font-weight: bold; font-size: 1em; margin-left: 20px; float: left;}

        .drugTwoSaveArea { margin: 20px; text-align: center; }
        .drugTwoSaveArea button { width: 100px; padding: 12px; text-align: center; font-weight: bold; font-size: 1em; margin-left: 20px; float: left;}

        .interactionSaveArea { margin: 20px; text-align: center; }
        .interactionSaveArea button { width: 100px; padding: 12px; text-align: center; font-weight: bold; font-size: 1em; float: left;}
    </style>
    
    

   
    <script>
        var numSavesRemaining = <?php echo $numSaves; ?>;
        var drug_one = <?php echo json_encode($drugs_both_static[0]); ?>;
        var drug_two = <?php echo json_encode($drugs_both_static[1]); ?>;
        var interaction = <?php echo json_encode($interaction_static); ?>;
        var numItemsRemaining = <?php echo $numItems; ?>;

        console.log("drug 1: " + drug_one);
        console.log("drug 2: " + drug_two);
        console.log("interaction: " + interaction);
    </script>
    
    <div class="imageArea">
        <?= $drugs[0] ?>
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

    <table width="100%">
        <tr>
            <td>
                <div class="drugOneSaveArea">
                    <button style="width:50%;" type="button" id="saveDrugOne" name="saveDrugOne" value="SAVE_DRUG_ONE">SAVE</button>  
                </div>
            </td>
            <td>
                <div class="drugTwoSaveArea">
                    <button style="width:50%;" type="button" id="saveDrugTwo" name="saveDrugTwo" value="SAVE_DRUG_TWO">SAVE</button>  
                </div>
            </td>

            <td>
                <div class="interactionSaveArea">
                    <button style="width:75%;" type="button" id="saveInteraction" name="saveInteraction" value="SAVE_INTERACTION">SAVE</button>  
                </div>
            </td>
        </tr>
    </table>

    <!-- <div class="precache textright">
            <input class="button button-trial-advance" id="FormSubmitButton" type="submit" value="Next" />
        </div> -->

    <style>
        .mcPicTable         {   margin: auto;   }
        .mcPicTable td      {   width: <?= $tdWidth ?>%;  vertical-align: middle; text-align: center;   min-width: 170px;   }
        .mcPicTable .button {   margin: 7% 11%;    }
    </style>

    <!-- show the image -->
    <div class="pic">
    
    </div>

    <div><?php echo $text; ?></div>

    <div class="oldNewAnsArea stage1">
        <div style="margin-top: 35px;"></div>
        <button style="width:30%; color: red;" type="button" id="savebuttdont" name="savebutt" value="DONTSAVE">DON'T SAVE</button>
        <!-- <button style="width:30%;" type="button" id="savebuttdo" name="savebutt" value="SAVE">SAVE</button> -->
    </div>

    <div class="textcenter finalSubmit">
        <input type="hidden" name="OldNew" id="OldNew"            value=""  >
        <input type="hidden" name="OldNewRT"          value="-1">
        <input type="hidden" name="drugonesavestatus" id="drugonesavestatus" value="-----">
        <input type="hidden" name="drugtwosavestatus" id="drugtwosavestatus" value="-----">
        <input type="hidden" name="interactionsavestatus" id="interactionsavestatus" value="-----">
        <input type="hidden" name="numsavestatus" id="numsavestatus" value="NOSELECTION">
        <input type="hidden" name="newnumsavesvalue" id="newnumsavesvalue" value="<?php echo $numSaves; ?>">
        <input type="hidden" name="numitemsremval" id="numitemsremval" value="<?php echo --$numItems; ?>">
        <input type="hidden" name="drugonestatic" id="drugonestatic" value="">
        <input type="hidden" name="drugtwostatic" id="drugtwostatic" value="">
        <input type="hidden" name="interactionstatic" id="interactionstatic" value="">
    </div>

    <script>

        var num_sub_elements_saved = 0;
        console.log("num_sub_elements_saved_init: " + num_sub_elements_saved);

        if(numSavesRemaining <= 0)
        {
            document.getElementById("savebuttdont").disabled = true;
            document.getElementById("saveDrugOne").disabled = true;
            document.getElementById("saveDrugTwo").disabled = true;
            document.getElementById("saveInteraction").disabled = true; 
            document.getElementById("savebuttdont").style.color = '#1010104D';
        }

        var phaseStartTime;

        console.log("Num saves:");
        console.log(numSavesRemaining);

        var save_clicks_made = 0;

        document.getElementById('drugonestatic').value = drug_one;
        document.getElementById('drugtwostatic').value = drug_two;
        document.getElementById('interactionstatic').value = interaction;

        $(".drugOneSaveArea button").on("click", function() {
            var choice = $(this).html();
            $("input[name='saveDrugOne']").val(choice);
            $(this).addClass("selectedChoice");

            $(".drugOneSaveArea button").prop("disabled", true);
            $(".oldNewAnsArea button").prop("disabled", true);

            document.getElementById('drugonesavestatus').value = drug_one;

            num_sub_elements_saved++;
            console.log("num_sub_elements_saved: " + num_sub_elements_saved);

            if(num_sub_elements_saved == 2) {
                $(".drugOneSaveArea button").prop("disabled", true);
                $(".drugTwoSaveArea button").prop("disabled", true);
                $(".interactionSaveArea button").prop("disabled", true);
            }

            if(save_clicks_made == 0) {
                document.getElementById("numsavestatus").value = "SAVE";
                document.getElementById("newnumsavesvalue").value = --numSavesRemaining;
            }

            save_clicks_made++;

            document.getElementById('drugonestatic').value = drug_one;
            document.getElementById('drugtwostatic').value = drug_two;
            document.getElementById('interactionstatic').value = interaction;

            document.getElementById("savebuttdont").style.color = '#1010104D';
        });

        $(".drugTwoSaveArea button").on("click", function() {
            var choice = $(this).html();
            $("input[name='saveDrugTwo']").val(choice);
            $(this).addClass("selectedChoice");

            $(".drugTwoSaveArea button").prop("disabled", true);
            $(".oldNewAnsArea button").prop("disabled", true);

            document.getElementById('drugtwosavestatus').value = drug_two;

            num_sub_elements_saved++;
            console.log("num_sub_elements_saved: " + num_sub_elements_saved);

            if(num_sub_elements_saved == 2) {
                $(".drugOneSaveArea button").prop("disabled", true);
                $(".drugTwoSaveArea button").prop("disabled", true);
                $(".interactionSaveArea button").prop("disabled", true);
            }

            if(save_clicks_made == 0) {
                document.getElementById("numsavestatus").value = "SAVE";
                document.getElementById("newnumsavesvalue").value = --numSavesRemaining;
            }

            save_clicks_made++;

            document.getElementById('drugonestatic').value = drug_one;
            document.getElementById('drugtwostatic').value = drug_two;
            document.getElementById('interactionstatic').value = interaction;

            document.getElementById("savebuttdont").style.color = '#1010104D';
        });

        $(".interactionSaveArea button").on("click", function() {
            var choice = $(this).html();
            $("input[name='saveInteraction']").val(choice);
            $(this).addClass("selectedChoice");

            $(".interactionSaveArea button").prop("disabled", true);
            $(".oldNewAnsArea button").prop("disabled", true);

            document.getElementById('interactionsavestatus').value = interaction;

            num_sub_elements_saved++;
            console.log("num_sub_elements_saved: " + num_sub_elements_saved);

            if(num_sub_elements_saved == 2) {
                $(".drugOneSaveArea button").prop("disabled", true);
                $(".drugTwoSaveArea button").prop("disabled", true);
                $(".interactionSaveArea button").prop("disabled", true);
            }

            if(save_clicks_made == 0) {
                document.getElementById("numsavestatus").value = "SAVE";
                document.getElementById("newnumsavesvalue").value = --numSavesRemaining;
            }

            save_clicks_made++;

            document.getElementById('drugonestatic').value = drug_one;
            document.getElementById('drugtwostatic').value = drug_two;
            document.getElementById('interactionstatic').value = interaction;

            document.getElementById("savebuttdont").style.color = '#1010104D';
        });


        $(".oldNewAnsArea button").on("click", function() {
            $("input[name='OldNewRT']").val(COLLECTOR.getRT());
            phaseStartTime = Date.now();
            var choice = $(this).html();
            $("input[name='OldNew']").val(choice);
            $(this).addClass("selectedChoice");
            
            $(".oldNewAnsArea button").prop("disabled", true);
            $(".drugOneSaveArea button").prop("disabled", true);
            $(".drugTwoSaveArea button").prop("disabled", true);
            $(".interactionSaveArea button").prop("disabled", true);

            document.getElementById('drugonestatic').value = drug_one;
            document.getElementById('drugtwostatic').value = drug_two;
            document.getElementById('interactionstatic').value = interaction;
                
            //  else {
                document.getElementById("numsavestatus").value = "NOTSAVE";
                document.getElementById("newnumsavesvalue").value = numSavesRemaining;
            // }
            document.getElementById("numitemsremval").value = --numItemsRemaining;
        });
        
    </script>
    

    <input class="hidden" name="Response" id="Response" type="text" value=""         />

    <input class="hidden" id="FormSubmitButton" type="submit" value="Submit"         />

   
