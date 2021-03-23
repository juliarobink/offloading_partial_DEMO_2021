<?php
/*  Collector
    A program for running experiments on the web
    Copyright 2012-2014 Mikey Garcia & Nate Kornell
 */
    require 'initiateCollector.php';
	
    $title = 'Basic Information';
    require $_codeF . 'Header.php';
?>
<div class="main-contain">
	<h2 class="textcenter">Basic Information</h2>

	<form name="Demographics" class="collector-form collector-form-extra" action="BasicInfoData.php" method="post" autocomplete="off">

		<div class="field">
			<legend>What is your gender?</legend>
			<input type="radio"   value="Male"     class="radio"    name="Gender"    />    Male     <br />
			<input type="radio"   value="Female"   class="radio"    name="Gender"    />    Female   <br />
			<input type="radio"   value="Other"   class="radio"    name="Gender"    />    Other   <br />
		</div>

		<div class="field">
			<p>What is your age?</p>
			<input type="text" value="" name="Age" autocomplete="off" class="forceNumeric" />
		</div>

		<div class="field">
			<p>Which of the following best describes your highest achieved education level?</p>
			<select name="Education">
				<option selected="selected">Select Level</option>
				<option>    Some High School                            </option>
				<option>    High School Graduate                        </option>
				<option>    Some college, no degree                     </option>
				<option>    Associates degree                           </option>
				<option>    Bachelors degree                            </option>
				<option>    Graduate degree (Masters, Doctorate, etc.)  </option>
			</select>
		</div>

	
		<div class="field">
			<p>Which of the following best describes your ethnicity?</p>
			<select name="Race">
				<option selected="selected">Select Level</option>
				<option>	American Indian/Alaskan Native		</option>
				<option>	Asian/Pacific Islander				</option>
				<option>	Black           					</option>
				<option>	White                       		</option>
				<option>	Hispanic/Latinx						</option>
				<option>	Other/unknwon						</option>
			</select>
		</div>

		<div class="field">
			<p>Do you speak English fluently?</p>
			<input    type="radio"    name="English"    value="Fluent"        />    Yes, I am fluent in English     <br />
			<input    type="radio"    name="English"    value="Non-Fluent"    />    No, I am not fluent in English  <br />
		</div>
        
		<div class="field">
			<p>What age did you start learning English? If English is your first language, please put 0.</p>
			<input type="text" value="" name="AgeEnglish" autocomplete="off"/>
		</div>

		<div class="field">
			<p>What is your country of residence?</p>
			<input type="text" value="" name="Country" size="30"    autocomplete="off" />
		</div>
		         <section class="radioButtons">
            <h3>Have you previously worked or do you currently work in any capacity in Uma Tauber's lab (AKA the Metacognition, Memory, and Aging Lab) at TCU?</h3>
            <label><input name="Uma" type="radio" value="Yes"   required/>Yes</label>
            <label><input name="Uma" type="radio" value="No"    required/>No</label>
        </section>
        
        <section class="radioButtons">
            <h3> Have you previously taken or are you currently taking the TCU Course "Memory and Cognition"?</h3>
            <label><input name="MemandCog" type="radio" value="Yes"   required/>Yes</label>
            <label><input name="MemandCog" type="radio" value="No"    required/>No</label>
        </section>
        
                
        <section class="radioButtons">
            <h3> Have you previously taken or are you currently taking the TCU Course "Experimental Psychology: Cognition"?</h3>
            <label><input name="CogLab" type="radio" value="Yes"   required/>Yes</label>
            <label><input name="CogLab" type="radio" value="No"    required/>No</label>
        </section>
        
        
     		<!-- ## SET ##  Use this area to provide the equivalent of an informed consent form -->
		<div class="consent">
			<h3 class="consent-legend">Informed Consent:</h3>
			<h3 class="consent-legend textcenter"></h3>
			<textarea rows="20" cols="45" wrap="physical">
			
Informed Consent to Participate in Research 

Title of Research:  Memory, Attention, and Learning
Principal Investigator: Dr. Mary B. Hargis, Ph.D.
Co-investigator:  Dr. Lauren Richmond, Ph.D.
 
Overview: You are invited to participate in a research study. In order to participate, you must be an undergraduate student at TCU or Stony Brook University (aged 18 or older), you must be fluent in English, and you must have normal or corrected-to-normal hearing and vision. Taking part in this research project is voluntary.

Study Details: This study is being conducted at TCU, at Stony Brook University, and/or online via Sona Systems. The purpose of this research is to better understand how well people can direct attention to information that they want to later remember. We are interested what information you remember, as well as how accurately you can predict how well you will remember this information.  

Participants: You are being asked to take part because you are over the age of 18 and volunteered to participate. If you decide to be in this study, you will be one of approximately 300 participants in this research study at TCU and/or at Stony Brook University. 

Voluntary Participation: Your participation is voluntary. You do not have to participate and may stop your participation at any time. If you withdraw from the study, you will be compensated for the time you spent. 

Confidentiality: Even if we publish the findings from this study, we will keep your information private and confidential. Anyone with authority to look at your records must keep them confidential.
  
What is the purpose of the research? The purpose of the study is to learn more about memory, attention, and motivation, how we remember different types of information, and how one can predict memory performance.

What is my involvement for participating in this study?  
If you choose to participate in this study, we would ask you to remember various types of information. For example, you might be presented with different types of words, text passages, images, or short statements, and you will be asked to remember them and/or make predictions about how well you think you will remember them.  In other cases, the words might be paired with numbers or monetary values that indicate how important it is to remember the information, and you will also make predictions about how well you will remember the words. We may also ask you to provide some basic demographic information about yourself, such as your age, your gender, and your ethnicity.

We expect your participation to take about 1-3 hours. At the end of the study you may be asked to return 1-2 weeks later to participate in additional studies. We will not share your individual performance on these tasks with you; your data will be anonymized and combined with many other participants’ data before any conclusions are drawn. 


Are there any alternatives and can I withdraw?
You do not have to participate in this research study. 

You should only take part in this study if you want to volunteer. You should not feel that there is any pressure to take part in the study. You are free to participate in this research or withdraw at any time. The decision to participate will not affect your student status.  

What are the risks for participating in this study and how will they be minimized?
COVID-19 risks will be minimized by having you participate online or in socially-distanced testing rooms. We don’t believe there are any risks from participating in this research that are different for risk that you encounter in everyday life, except possibly mild boredom.

What are the benefits for participating in this study?
Although you will not directly benefit from being in this study, others might benefit because the results of the research may contribute to a better understanding of memory, attention, motivation, and learning.

Will I be compensated for participating in this study?
You will receive course credit for your participation. If you choose to withdraw from the study, you will be compensated for the time you have spent (for students, 1 class credit). 

What are my costs to participate in the study?
To participate in the research, you will not need to pay for any costs. 

Is there any conflict of interest?
No.

How will my confidentiality be protected?  
Every effort will be made to limit the use and disclosure of your personal information, including research study records, to people who have a need to review this information. We cannot promise complete secrecy. Your records may be reviewed by authorized University personnel or other individuals who will be bound by the same provisions of confidentiality.  
We may publish what we learn from this study. If we do, we will not include your name. We will not publish anything that would let people know who you are.

What will happen to the information collected about me after the study is over?
We will keep your research data to use for future research or other purpose. Your name and other information that can directly identify you will be kept secure and stored separately from the research data collected as part of the project. 
We may share your research data with other investigators without asking for your consent again, but it will not contain information that could directly identify you. We may upload your deidentified data to an open science repository such as the Open Science Framework (https://osf.io/). We will not share your research data with other investigators.

Who should I contact if I have questions regarding the study or concerns regarding my rights as a study participant? 
You can contact Dr. Mary Hargis at m.hargis@tcu.edu, 817-257-4777 with any questions that you have about the study.

Dr. Dru Riddle, Chair, TCU Institutional Review Board, (817) 257-6811, d.riddle@tcu.edu; or Dr. Floyd Wormley, Associate Provost of Research, research@tcu.edu

If you are participating online: By selecting "Agree to participate" below, you are agreeing to be in this study. Make sure you understand what the study is about before you agree. You will be given a copy of this document for your records upon request. If you have any questions about the study after you agree to participate, you can contact the study team using the information provided above.
 
</textarea>
</section>
		
	
        
        
        <section>
            <label>
                <h3>I am age 18 or older.</h3>
                <select name="Age18" required class="wide collectorInput">
                    <option value="" default selected>Select one</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </section>
        
        <section>
            <label>
                <h3>I have read the consent form above.</h3>
                <select name="Read" required class="wide collectorInput">
                    <option value="" default selected>Select one</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </section>
        
        <section>
            <label>
                <h3>I want to participate in this research and continue with the experiment.</h3>
                <select name="Consent" required class="wide collectorInput">
                    <option value="" default selected>Select one</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </section>
        
                <section>
            <label>
                <h3>If you answered "No" to ANY of the last three questions, submit this page and then close the browser window.</h3>
            
                </select>
            </label>
        </section>

		</textarea>
		</div>

		<div class="consent textcenter">
			<input class="button" type="submit" value="Submit Basic Info" />
		</div>
	</form>
</div>
<?php
    require $_codeF . 'Footer.php';