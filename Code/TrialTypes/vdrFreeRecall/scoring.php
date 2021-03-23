<?php
/*  Collector
    A program for running experiments on the web
    Copyright 2012-2014 Mikey Garcia & Nate Kornell
 */
 
	// When using custom scoring, every scoring file must start with something
	// like the following 3 lines of code.
	// This is because in Login.php, the program will actually load each of
	// these scoring files, in order to find the columns (keys) needed in the
	// output file of this experiment.
	// So, first check for $findingKeys, and if that evaluates to true, return
	// an array of the columns needed to properly record all the data for this
	// trial.
	// This must be done, even if there are no new columns being added.
    if ($findingKeys) {
        return array('RT', 'Response', 'Accuracy', 'RTkey', 'RTlast', 'strictAcc', 'lenientAcc', 'strictVal', 'lenientVal', 'possibleVal', 'matchedAns', 'unmatchedAns', 'unmatchedResp', 'Errors', 'Selectivity_Index');
    }
	
	// to make sure that everything is recorded, just throw all of POST into
	// $data, which will eventually be recorded into the output file.
	// To create new columns, simply assign the new data you want to record
	// to a new key in $data.
	// For example,    $data[ 'New Column' ] = "Hello";    would make a new
	// column titled "New Column" in the output, and every row for this trial
	// type would have the value "Hello".
    $data = $_POST;
	
	function DamLevLimit( $str1, $str2, $limit ) {
		$str1 = trim(strtolower($str1));
		$str2 = trim(strtolower($str2));
		if( $str1 === $str2 ) {
			return 0;
		}
		if( abs( strlen($str1) - strlen($str2) ) > $limit ) { return FALSE; }
		if( $str1 === '' ) {
			if( strlen($str2) > $limit ) { return FALSE; }
			else {
				return strlen( $str2 );		//if first is empty, length of second
			}
		}
		elseif( $str2 === '' ) {
			if( strlen($str1) > $limit ) { return FALSE; }
			else {
				return strlen( $str1 );			//and if only the second is empty, length of the first
			}
		}
		//after this, i understand nothing
		$score = array();
		
		$inf = strlen($str1) + strlen($str2);
		$score[0][0] = $inf;
		for( $i=0; $i<=strlen($str1); $i++ ){
			$score[$i+1][1] = $i;
			$score[$i+1][0] = $inf;
		}
		for( $i=0; $i<=strlen($str2); $i++ ){
			$score[1][$i+1] = $i;
			$score[0][$i+1] = $inf;
		}
		
		$sd = array();
		$strComb = $str1.$str2;
		for( $i=0; $i<strlen($strComb); $i++ ){
			$sd[ $strComb[$i] ] = 0;
		}
		
		for( $i=1; $i<=strlen($str1); $i++ ) {
			$db = 0;
			for( $j=1; $j<=strlen($str2); $j++ ) {
				$i1 = $sd[$str2[$j-1]];
				$j1 = $db;
				
				if( $str1[$i-1] === $str2[$j-1] ) {
					$score[$i+1][$j+1] = $score[$i][$j];
					$db = $j;
				}
				else {
					$score[$i+1][$j+1] = min( $score[$i][$j], $score[$i+1][$j], $score[$i][$j+1] )+1;
				}
				
				$score[$i+1][$j+1] = min( $score[$i+1][$j+1], ($score[$i1][$j1] + $i - $i1 + $j - $j1 -1) );
			}
			$sd[$str1[$i-1]] = $i;
			if( $score[$i][max(1,$j+$i-strlen($str1)-1)] > $limit ) {
				return FALSE; 
			}
		}
		if( $score[strlen($str1)+1][strlen($str2)+1] > $limit ) {
			return FALSE;
		}
		return $score[strlen($str1)+1][strlen($str2)+1];
	}
	
	$data = $_POST;
	
	$answers = explode( '|', $answer );
	
	$unacceptable = array();
	if( isset( $currentTrial['Stimuli']['Unacceptable Answers'] ) ) {
		$unaccAns = explode( '|', $currentTrial['Stimuli']['Unacceptable Answers'] );
		foreach( $unaccAns as $unacc ) {
			$temp = explode( ',', $unacc );
			foreach( $temp as &$t ) {
				$t = trim($t);
			}
			unset($t);
			$unacceptable[] = $temp;
		}
	} else {
		foreach( $answers as $ans ) {
			$unacceptable[] = array();
		}
	}
	
	$leniency = array();
	if( isset( $currentTrial['Stimuli']['Leniency'] ) ) {
		$leniency = explode( '|', $currentTrial['Stimuli']['Leniency'] );
		foreach( $leniency as &$len ) {
			$len = (int) $len;
		}
		unset($len);
	} else {
		foreach( $answers as $ans ) {
			$leniency[] = 1;
		}
	}
	
	$value = array();
	if( isset( $currentTrial['Stimuli']['Value'] ) ) {
		$value = explode( '|', $currentTrial['Stimuli']['Value'] );
		foreach( $value as &$val ) {
			$val = (float) $val;
		}
		unset($val);
	} else {
		foreach( $answers as $ans ) {
			$value[] = 1;
		}
	}
	
	$responseFormatted = strtolower(preg_replace("/[^a-zA-Z0-9'\- ]+/", " ", $_POST['Response'] ));	//replace most symbols with spaces, so that if they entered like word,word,word, we get separate words
	$responseFormatted = trim(preg_replace( "/\s+/", " ", $responseFormatted ));			//then, set all spaces and newlines to a single space.  this assumes that the answers dont have non-alphanumerical characters in them
	$respExp = explode( ' ', $responseFormatted );
	$damLevByAns = array();
	$damLevByRes = array();
	foreach( $answers as $i => $ans ) {
		foreach( $respExp as $j => $res ) {
			if( in_array( $res, $unacceptable[$i] ) ) { continue; }
			if( (substr($res,-1)==='y' AND substr($ans,-3)==='ies') OR (substr($ans,-1)==='y' AND substr($res,-3)==='ies') ) { $plural = 2; } else { $plural = 0; }		//no penalties for knowing how to spell
			$dist = DamLevLimit( $ans, $res, $leniency[$i]+$plural );
			if( $dist === FALSE ) { continue; }
			$damLevByRes[ $j ][ $i ] = $dist;
			$damLevByAns[ $i ][ $j ] = $dist;
		}
	}
	$match = array();
	while( count($damLevByAns, TRUE) !== count($damLevByAns) ) {			//keep going until all of our Answer rows are empty, meaning they have had all possible matches removed
		foreach( $damLevByAns as $i => $resArray ) {
			foreach( $resArray as $j => $diff ) {
				if( $diff === min($resArray) AND $diff === min($damLevByRes[$j]) ) {
					$match[$i]['word'] = $respExp[$j];
					$match[$i]['diff'] = $diff;
					foreach( $damLevByRes[$j] as $i2 => $diff2 ) {			//remove all references to this match in both arrays, along their columns and rows
						unset( $damLevByAns[$i2][$j] );
					}
					foreach( $damLevByAns[$i] as $j2 => $diff2 ) {
						unset( $damLevByRes[$j2][$i] );
					}
					unset( $damLevByAns[$i] );
					unset( $damLevByRes[$j] );
					break 2;
				}
			}
		}
	}
	
	$matchedAnswers = array();
	$differences = array();
	$data['possibleVal'] = array_sum($value);
	$data['lenientVal']  = 0;
	$data['strictVal']   = 0;
	$data['lenientAcc']  = 0;
	$data['strictAcc']   = 0;
	foreach( $answers as $i => $ans ) {
		if( isset( $match[$i] ) ) {
			$matchedAnswers[$i] 	= $match[$i]['word'];
			$unmatchedAnswers[$i] 	= '_';
			$differences[$i]		= $match[$i]['diff'];
			$data['lenientAcc']++;
			$data['lenientVal'] += $value[$i];
			if( $match[$i]['diff'] === 0 ) {
				$data['strictAcc']++;
				$data['strictVal'] += $value[$i];
			}
		}
		else {
			$matchedAnswers[$i] 	= '_';
			$unmatchedAnswers[$i] 	= $ans;
			$differences[$i]		= '_';
		}
	}
	$data['matchedAns'] 	= implode( '|', $matchedAnswers 	);	//we can see which words were identified
	$data['unmatchedAns'] 	= implode( '|', $unmatchedAnswers 	);	//we can see which words were not identified
	$unmatchedResp = array();
	foreach( $respExp as $resp ) {
		$found = FALSE;
		foreach( $matchedAnswers as $i => $ans ) {
			if( $resp === trim(strtolower( $ans )) ) {
				$found = TRUE;
				unset( $matchedAnswers[$i] );
				break;
			}
		}
		if( !$found ) {
			$unmatchedResp[] = $resp;
		}
	}
    $siChance = $data['possibleVal']/count($answers)*$data['lenientAcc'];
    $siBest = 0;
    sort($value);
    for( $i=0; $i<$data['lenientAcc']; ++$i ) {
        $siBest += array_pop($value);
    }
    if ($siBest !== $siChance)
    {
        $data['Selectivity_Index'] = ($data['lenientVal']-$siChance)/($siBest-$siChance);
    }
    else
    {
        $data['Selectivity_Index'] = '';
    }
	$data['Accuracy'] = $data['lenientAcc']/count($answers);
	$data['unmatchedResp'] 	= implode( '|', $unmatchedResp 	);
	$data['Errors'] 		= implode( '|', $differences 	);	//and we can see how far off they were (so if we set leniency = 2, we can still which were 0, 1, or 2 off)
