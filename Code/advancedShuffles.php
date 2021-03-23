<?php
	function findSetting( &$settings, $target, $removeFound = TRUE ) {
		foreach( $settings as $key => $setting ) {
			$test = removeLabel( $setting, $target );
			if( $test !== FALSE ) {
				if( $removeFound ) {
					unset( $settings[$key] );
				}
				return $test;
			}
		}
		return FALSE;
	}
	
	function shuffleAssoc( &$array ) {
		$keys = array_keys( $array );
		$vals = array_values( $array );
		shuffle($vals);
		foreach( $keys as $i => $key ) {
			$array[ $key ] = $vals[$i];
		}
	}
	
	function findIndex( $array, $search, $errorOrig ) {
        // this requires the column headers to have been lower-cased, to correctly find "x" rather than "X"
        $search = strtolower(trim($search));
		$flip = array_change_key_case(array_flip(array_values($array)), CASE_LOWER);
		if( isset( $flip[ $search ] ) ) {
			return $flip[ $search ];
		} elseif( is_numeric( $search ) ) {
			--$search;
			if( !isset( $array[ $search ] ) ) exit( '<h4>Error</h4> <p>Search error within the "Target" or "Within" setting of a shuffle column "'.$errorOrig.'": The search "'.(++$search).'" is bigger than the total number of columns ('.count($array).').</p>' );
			return $search;
		} elseif( ctype_alpha( $search ) AND strlen($search) === 1 ) {
			$temp = count( range( "a", $search ) ) - 1;
			if( !isset( $array[ $temp ] ) ) exit( '<h4>Error</h4> <p>Search error within the "Target" or "Within" setting of a shuffle column "'.$errorOrig.'": The search "'.($search).'" is not the letter code for an existing column.</p>' );
			return $temp;
		} else {
			exit( '<h4>Error</h4> <p>Search error within the "Target" or "Within" setting of a shuffle column "'.$errorOrig.'": The search "'.$search.'" is not a literal column header, a numeric search, or a single letter code.  If you are trying to use a shortcut for a column past "z", please use a numeric code instead.</p>' );
		}
	}
	
	function shuffle2dArray( $array, $debug = FALSE ) {
		$notShuffle = array(
			 '' 	=> TRUE
			,'0' 	=> TRUE
			,'off' 	=> TRUE
			,'no' 	=> TRUE
		);
		
		if( $debug ) $startCopy = $array;
        
        $padding  = array();
		$firstRow = current($array);
        while (!is_array($firstRow)) {
            $padding[] = array_shift($array);
            $firstRow = current($array);
        }
		
		$headers = array_keys( $firstRow );
		
		$prevColumns = array();
		// go through each of the headers, and see if it contains shuffle instructions
		foreach( $headers as $header ) {
			// if the column doesn't explicitly say which columns to target,
			// then we will just use the previous columns as the target
			$prevColumns[ $header ] = TRUE;
			if( stripos( $header, 'advshuffle' ) !== FALSE ) {
				$shuffleInfo = explode( ';', $header );
				// the type of shuffle (simple, block, or list), should be before the first semicolon
				$type = array_shift( $shuffleInfo );
				
				$target = findSetting( $shuffleInfo, 'target' );
				if( is_bool($target) ) {
					// if FALSE, "target" wasn't found.  
					// if TRUE, they had the "target" keyword, but no columns
					// either way, just shuffle the columns up to this point
					$targets = $prevColumns;
				} else {
					// targets can be specified as ranges, like "Target: 1,2,5-8,10"
					$target = explode( ',', $target );
					$targets = array();
					foreach( $target as $targ ) {
						$these = explode( '-', $targ );
						$first = array_shift( $these );
						$first = findIndex( $headers, $first, $header );
						if( $these !== array() ) {
							// if they did specify a range (e.g. 1-3), $these won't be empty after array_shift
							// if so, get all the columns in this range, and add them to $targets
							$last = array_pop( $these );
							$last = findIndex( $headers, $last, $header );
							$targRange = range( $first, $last );
							foreach( $targRange as $t ) {
								$targets[ $headers[$t] ] = TRUE;
							}
						} else {
							$targets[ $headers[ $first ] ] = TRUE;
						}
					}
				}
				// targets should include itself, so that the shuffle column will reflect its own changes
				$targets[ $header ] = TRUE;
				
				$within = findSetting( $shuffleInfo, 'within' );
				if( is_bool($within) ) {
					// if they didn't specify a within column, just make a temp array
					// and pretend everything is in the same within category
					$within = array();
					foreach( $array as $i => $row ) {
						$within[$i] = '1';
					}
				} else {
					$within = findIndex( $headers, $within, $header );
					$withinCol = $headers[ $within ];
					// add the within to the targets, so that if block shuffle
					// affects the order of the non-shuffled rows, we can still see that
					// also, we can display the within during debugging
					$targets[ $withinCol ] = TRUE;
					$within = array();
					foreach( $array as $i => $row ) {
						$within[$i] = $row[ $withinCol ];
					}
				}
				
				// we just want to grab the target columns from each of the rows
				$shuffled = array();
				foreach( $array as $i => $row ) {
					foreach( $targets as $t => $unused ) {
						$shuffled[$i][$t] = $row[$t];
					}
				}
				
				if( $debug ) {
					echo '<div style="white-space: nowrap">';
					echo 'Shuffle Column: '.$header.'<br>';
					echo '<div style="display: inline-block">';
					echo 'Before shuffle:<br>';
					display2dArray( $shuffled );
					echo '</div>';
				}
				
				if( 	  stripos( $type, 'block' ) !== FALSE ) {
					superBlockShuffle(  $shuffled, $header, $within, $notShuffle );
				} elseif( stripos( $type, 'list'  ) !== FALSE ) {
					listShuffle(   $shuffled, $header, $within, $notShuffle );
				} else {
					simpleShuffle( $shuffled, $header, $within, $notShuffle );
				}
				
				if( $debug ) {
					echo '<div style="display: inline-block">';
					echo 'After shuffle:<br>';
					display2dArray( $shuffled );
					echo '</div>';
					echo '</div>';
				}
				
				// put the shuffled result back in the array
				foreach( $shuffled as $i => $row ) {
					foreach( $row as $t => $value ) {
						$array[$i][$t] = $value;
					}
				}
			}
		}
        
        foreach (array_reverse($padding) as $row) {
            array_unshift($array, $row);
        }
        
		if( $debug ) {
			echo '<div style="white-space: nowrap">';
			echo '<div style="display: inline-block">';
			echo 'Before all shuffles:<br>';
			display2dArray( $startCopy );
			echo '</div>';
			echo '<div style="display: inline-block">';
			echo 'After shuffles:<br>';
			display2dArray( $array );
			echo '</div>';
			echo '</div>';
		}
        return $array;
	}
	
	/*
		Shuffle functions
		
		&$array is the 2d array to be shuffled.  I didn't 
			have associative arrays in mind when I made these
			functions, so I'm not sure if non-numeric row keys
			will work.
			
			Of course, the sub-arrays (columns) should be
			associative.
		
		$shuffle is the column name that is being used to guide
			the shuffle.  It is expected to exist inside $array
		
		$within is a new array, which has the same indices as
			$array, but is only 1-dimensional.  Its values
			should be scalar, not arrays (e.g. "1", "2", "yes",
			"no", "" )
	
		$notShuffle should be the values treated as "off".  
			shuffle2dArray() sets these to '', 'off', 'no', and
			'0'. The keys should be set to these "off" labels,
			but the values can be anything except NULL.
			
			Both the "shuffle" column and the $within value for
			that row need to be different than the $notShuffle
			keys to be shuffled.
	*/
	
	/* 	simpleShuffle
		any rows with the same value in the same "within" value
		will be shuffled together.
	*/
	function simpleShuffle( &$array, $shuffle, $within, $notShuffle ) {
		$indices = array();
		foreach( $array as $i => $row ) {
			if( 	!isset( $notShuffle[ strtolower( $within[$i]    ) ] ) 
				AND !isset( $notShuffle[ strtolower( $row[$shuffle] ) ] )
			) {
				$indices[ $within[$i] ][ $row[$shuffle] ][$i] = $i;
			}
		}
		foreach( $indices as $w ) {
			foreach( $w as $set ) {
				$old = array();
				foreach( $set as $i ) {
					$old[$i] = $array[$i];
				}
				shuffleAssoc($set);
				foreach( $set as $i => $new ) {
					$array[$i] = $old[ $new ];
				}
			}
		}
	}
	
	/*	blockShuffle
		as long as two consecutive rows have the same value in
		their shuffle column and their $within setting, they will
		be grouped into the same block.  Then, shuffled blocks
		will be shuffled, and the array will be reconstructed by
		going through each block and writing each row back into
		the array.
		
		Its possible to affect non-shuffled rows with this.  If
		a block with 2 rows is shuffled with a block with 4 rows,
		then everything under the original 2-row block will be moved
		down to make way for the 4-row block.
	*/
	function superBlockShuffle( &$array, $shuffle, $within, $notShuffle ) {
		$blocks = array();
		$blocksToShuffle = array();
		$currentBlock = -1;
		$prevWith = FALSE;
		$prevCode = FALSE;
		$shufflePatterns = array();
		foreach( $array as $i => $row ) {
			// first, if this is a non-shuffled row, standardize this value
			if( 	isset( $notShuffle[ strtolower( $within[$i]    ) ] ) 
				OR 	isset( $notShuffle[ strtolower( $row[$shuffle] ) ] )
			) {
				$withCode    = 'off';
				$shuffleCode = 'off';
			} else {
				$withCode = $within[$i];
				$shuffleCode = $row[$shuffle];
			}
			// check to see if we are starting a new block
			if( $withCode !== $prevWith OR $shuffleCode !== $prevCode ) {
				++$currentBlock;
				// if we are starting a block that doesn't need a shuffle,
				// simply have it point to itself
				// otherwise, put it in a new block, which will be shuffled
				if( $shuffleCode === 'off' ) {
					$shufflePatterns[ $currentBlock ] = $currentBlock;
				} else {
					$blocksToShuffle[ $withCode ][ $currentBlock ] = $currentBlock;
				}
				$prevCode = $shuffleCode;
				$prevWith = $withCode;
			}
			// make a list of all blocks, so we can write them back into $array at the end
			$blocks[ $currentBlock ][] = $i;
		}
		// shuffle around the blocks, which are pointing to themselves originally
		// e.g., array( a => a, b => b, c => c ) becomes array( a => b, b => c, c => a )
		foreach( $blocksToShuffle as $blockedWithin ) {
			shuffleAssoc($blockedWithin);
			foreach( $blockedWithin as $orig => $new ) {
				$shufflePatterns[ $orig ] = $new;
			}
		}
		$newArray = array();
		// foreach block, use the block that this points to in shufflePatterns
		// if its an unshuffled block, it will point to itself
		// with each of these, get each row number in that block, and write those
		// rows into the new array.  Once we are done, replace array with newArray
		foreach( $blocks as $n => $block ) {
			foreach( $blocks[ $shufflePatterns[$n] ] as $i ) {
				$newArray[] = $array[$i];
			}
		}
		$array = $newArray;
	}
	
	/* 	listShuffle
		shuffled items are categorized into lists, and then each
		shuffled list is pointed at another shuffled list.  Then
		for each row, if that row belongs to a shuffled list, it
		is replaced with the next row from the matching 
		replacement list.
		
		If one list is longer than its replacement, the
		replacement will start re-using rows, starting back at 
		the first row.  If the replacement is too long, un-used
		rows will simply be lost.
		
		Example:
		
			Cue		Target		ListShuffle					Cue		Target		ListShuffle	
		 1	a 		apple		1 						 1	b 		bear 		2	
		 2	b 		bear 		2 						 2	a 		apple		1 
		 3	c 		cucumber 	1 						 3	d 		dog 		2
		 4	d 		dog 		2 				=>		 4	c 		cucumber 	1
		 5	e 		eggplant 	1  						 5	f 		fish 		2 
		 6 	f 		fish 		2 						 6 	e 		eggplant 	1  
		 7 	g 		grapes 		1 						 7 	h 		horse 		2
		 8  h 		horse 		2 						 8  g 		grapes 		1 
	*/
	
	function listShuffle( &$array, $shuffle, $within, $notShuffle ) {
		$newArray = array();
		$lists = array();
		// first, group row numbers into lists, group lists into withins
		foreach( $array as $i => $row ) {
			if( 	!isset( $notShuffle[ strtolower( $row[ $shuffle ] ) ] )
				AND	!isset( $notShuffle[ strtolower( $within[ $i ]    ) ] )
			) {
				$lists[ $within[$i] ][ $row[ $shuffle ] ][] = $i;
			}
		}
		// shuffle the contents of each list
		foreach( $lists as &$listWithin ) {
			shuffleAssoc( $listWithin );
		}
		foreach( $array as $i => $row ) {
			// check if the row is a shuffled one
			if( isset( $lists[ $within[$i] ][ $row[ $shuffle ] ] ) ) {
				// if so, get the number of the next row in the replacement list
				// once we have the row number, we can record the new line, with the current index as the key
				$index = each( $lists[ $within[$i] ][ $row[ $shuffle ] ] );
				// if each() has reached the end of an array, use the first row in that list instead
				// also, we need to increment the array's internal pointer, since using reset() will
				// return the first value without moving the pointer to the next row
				if( $index === FALSE ) {
					$newArray[$i] = $array[ reset( $lists[ $within[$i] ][ $row[ $shuffle ] ] ) ];
					next( $lists[ $within[$i] ][ $row[ $shuffle ] ] );
				} else {
					$newArray[$i] = $array[ $index[1] ];
				}
			}
		}
		// replace each shuffled row with its new row
		foreach( $newArray as $i => $row ) {
			$array[$i] = $row;
		}
	}
?>