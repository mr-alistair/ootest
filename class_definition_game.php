<?php

Class Game {
  //properties
  public $g_movecounter; //for logging
  public $g_movelog = array();
  public $g_playerturn;
  public $g_players = array();
  public $g_dievalue;
  public $g_gameover;

  function __construct() {
    $this->g_movecounter = 0;
    $this->g_playerturn = rand( 1, 2 );
    $this->g_logmove( "Player " . $this->g_playerturn . " will go first." );
    $this->g_dievalue = 0;
    $this->g_gameover = FALSE;
    
  }

  function __destruct() {
    echo "<br>= = =LOG STARTS = = = = ";

    foreach ( $this->g_movelog as $x_log_out ) {
      echo "<br>" . $x_log_out;
    }


  }

  public function g_logmove( $x_logmove ) {
    $this->g_movecounter++;
    $this->g_movelog[ $this->g_movecounter ] = date( "h:i:s" ) . " -- " . $x_logmove;
  }

  //methods
  public function g_newgame() {
    //create players and assign markers
    $this->g_players[ 1 ] = new Player( 1 );
    $this->g_logmove( "Player 1 created.");
   
    $this->g_players[ 2 ] = new Player( 2 );
    $this->g_logmove( "Player 2 created.");

  }


  public function g_get_playerturn() {
    return $this->g_playerturn;
  }
  
  public function g_flip_player() {
      if ($this->g_playerturn == 1) {
          $this->g_playerturn = 2;
          }
          else
          {
          $this->g_playerturn = 1;
         
      }
  }
  
  public function g_return_other_player() {
      if ($this->g_playerturn == 1) {
          return  $this->g_players[ 2 ];
          }
          else
          {
          return   $this->g_players[ 1 ];
      }
  }

  //D - find a marker not on the board, select one to move
  public function g_find_to_move_onto_board(Player $x_player) {
      
      //temp properties
      $x_piece_array_pointer = array();
      
      $x_counter_array = 0;
      
    
   for ( $x_counter = 1; $x_counter <= 4; $x_counter++ ) {

        //find a player's marker which is off the board but active
      if($x_player->p_pieces[$x_counter]->m_get_location() == 1)  {
                //action
      
          $x_piece_array_pointer[++$x_counter_array] = $x_counter;
      } 
    }   

    //If there is one or more, return one at random
    if ($x_counter_array > 0){
        
      
        return($x_piece_array_pointer[rand(1,$x_counter_array)]);
    } 
    else
    { 
        //there were no markers 'off the board'...return an empty pointer;
      
        $this->g_logmove( "Player " . $x_player->p_get_playerid() . " was in SET D but could not find markers to move onto the board...");
        
        return 0;
    }
  }

  public function g_diceroll() {
    
    $this->g_dievalue = rand (1, 6);
    
    return ($this->g_dievalue);
  
      
  }

  public function g_return_player( $x_playerid ): Player {
    
    return $this->g_players[ $x_playerid ];
  
  }

  public function g_find_to_move_in_play(Player $x_player) 
  {
      
    //temp properties
    $x_piece_array_pointer = array();
  //  $x_piece_array_backup = array();
    
    $x_counter_array = 0;
    $x_temp_value = 0;
    
    $x_temp_magic_numbers = array(20,24,30,40,60);
    
        for ( $x_counter = 1; $x_counter <= 4; $x_counter++ )
        {

        //find a player's marker which is active

            if($x_player->p_pieces[$x_counter]->m_get_status() ) 
            {
                $x_counter_array++;
                $x_piece_array_pointer[$x_counter_array] = $x_counter;

            }
        }

    //we now have an array of active pointers... if we can pick one that won't bust and is not a special number, do that, otherwise find one at random.

        $x_piece_array_backup = array_values($x_piece_array_pointer);

        
        //If there is one or more, return one at random
    if ($x_counter_array > 0)
    {

        $this->g_logmove("Considering between ".$x_counter_array." potential piece(s).");


        for ($x_counter_loop = 1;$x_counter_loop<=$x_counter_array;$x_counter_loop++)
        {
            $x_test_1 = FALSE;
            $x_test_2 = FALSE;

            $x_temp_value = $x_piece_array_pointer[$x_counter_loop];

            $this->g_logmove("Step ".$x_counter_loop." of ".$x_counter_array."..Looking at piece: ".$x_temp_value. " at location ".$x_player->p_pieces[$x_temp_value]->m_get_location());
            
            $x_test_1 = in_array($x_temp_value,$x_temp_magic_numbers);
            
            if($x_test_1) 
            {
                $this->g_logmove("Considering ignoring piece ".$x_temp_value. " as it is on a penultimate number.");
            }
            
            if(($x_player->p_pieces[$x_temp_value]->m_get_location() * $this->g_dievalue) > 120)
            { 
                $x_test_2 = TRUE;
                $this->g_logmove("Considering ignoring piece ".$x_temp_value. " as it may cause a blowout.");
            }
            
            if ($x_test_1 || $x_test_2) 
            {
                //one of these is a 'good' number we wish to avoid moving randomly, or could cause a blowout
                
                unset($x_piece_array_backup[($x_counter_loop-1)]);
            }
        
        }
        
            //compressing      
            if (sizeof($x_piece_array_backup) > 0) {
                
                //copy array into original and reset pointer count

                $x_piece_array_pointer = array_values($x_piece_array_backup);

            }
        
            else
        
            {
                $this->g_logmove("Ignored too many...reverting.");
                $x_piece_array_pointer = array_values($x_piece_array_pointer);
            }

    
            //pick a pointer at random from the remainder
            
            $this->g_logmove( "Player " . $x_player->p_get_playerid() . " has " . sizeof($x_piece_array_pointer)." possible piece(s) to move which are on the board." );
        
            $x_counter_array = array_rand($x_piece_array_pointer,1);

            $this->g_logmove( "Player " . $x_player->p_get_playerid() . " has chose to move " . $x_piece_array_pointer[$x_counter_array]);
            
        }
    
    
    
    else
    
    {
        
        //there were no markers 'on the board'...return an empty pointer;
        //this is captured by the calling function and acted upon        
        
        return 0;
    }
  
        return($x_piece_array_pointer[$x_counter_array]);
  
  } //eofunc


  //core calling function from controlling class
  public function g_player_action(Player $x_player ) {

    //properties
    $x_result = FALSE;

    $this->g_dievalue = $this->g_diceroll();

    $this->g_logmove( "Player " . $x_player->p_get_playerid() . " rolled a " . $this->g_dievalue );
    
    if($this->g_dievalue == 1) {
        
     $this->g_logmove( "<font color=red>Player " . $x_player->p_get_playerid() . " has to forfeit their move!</font>");
    } 
        else
    {
        //call function A here
        
        $x_temp_magic_numbers = array(20,24,30,40,60,120);
        
        $x_result = $this->g_target_magic_numbers($x_player, $x_temp_magic_numbers,"penultimate");
        
        
        
        //call function B here
        
        if (!$x_result) 
        {
            $this->g_logmove("Player ".$x_player->p_get_playerid()." did not find any penultimate targets.");
            
            $x_temp_magic_numbers = array(2,3,4,5,6,8,9,10,12,15,25,50);
    
            $x_result = $this->g_target_magic_numbers($x_player, $x_temp_magic_numbers,"factor");
        }
        
        if (!$x_result) 
        {
                //call function C here, return TRUE if it triggers successfully
            
                $this->g_logmove("Player ".$x_player->p_get_playerid()." did not find any factor targets.");
            
                $x_result = $this->g_target_potential_clashes_set_C($x_player);
        
        //call function D if C did not trigger
        }
        
        if (!$x_result) 
        {
                $x_result = $this->g_move_onto_board_set_D($x_player); //returns whether or not this function was activated...not sure what to do with this flag
        }
        
    }

    //end of game - could be a separate function?
    if($this->g_gameover) 
    {
        
        echo "<font color=blue><b>**************************************</b></font><br>";
        echo "<font color=red><b>***   Player " . $x_player->p_get_playerid() . " HAS WON THE MATCH! ***</b></font><br>";
        echo"<font color=DARKGREEN><b>**************************************</b></font><br>";
        
        
        $this->g_logmove( "<font color=blue><b>************************************</b></font>");
        
        $this->g_logmove( "<font color=red><b>***   Player " . $x_player->p_get_playerid() . " HAS WON THE MATCH! ***</font>");
        
        
        $this->g_logmove( "<font color=DARKGREEN><b>************************************</b></font>");
        
    }

    return $this->g_gameover;

} //end of g_player_action


public function g_detect_clash($x_location, $x_player) {
    
    //iterate through opposing players active pieces and reset them if the new move has caused a clash
    
      //temp properties
     // $x_piece_array_pointer = array();
      $x_counter_array = 0;
      $x_return_flag = FALSE;
      
   for ( $x_counter = 1; $x_counter <= 4; $x_counter++ ) {

        //find an (opposition) player's marker which is on the board but active
      if($x_player->p_pieces[$x_counter]->m_get_location() == $x_location && $x_player->p_pieces[$x_counter]->m_get_status())
      {
        //bump the clash piece
        
        $this->g_dievalue = 0;
        
        $this->g_marker_move($x_player, $x_counter);
        
        $this->g_logmove( "<font color=purple>Player " . $x_player->p_get_playerid() . "'s piece ". $x_counter . " was bumped to the start of the board!</font>");
        
        $x_return_flag = TRUE;
        
      } //if it doesn't find a clash, do nothing
      
    }   
    
    return $x_return_flag;
}

public function g_target_magic_numbers (Player $x_player, array $x_magicnumbers, string $x_type) {

    //properties
    $x_forecast_pointers = array();
    $x_found_target = FALSE;
    $x_selection_pointers = array();
    $x_piece_pointer = 0;
    $x_temp_target = array();

    //populate current players positions and status to temp array
    for($x_counter = 1;$x_counter <= 4; $x_counter++)
    {
        
        if(!$x_player->p_pieces[$x_counter]->m_get_status()) 
        {
            //this piece is out of play - set up a dummy which will never get hit
        
            $x_forecast_pointers[$x_counter] = array($x_counter,999,FALSE);
            
            
        } 
        
        else
        
        {
            //otherwise, put in a forecast of where it would land based on the dice roll
         
            $x_forecast_pointers[$x_counter] = array($x_counter, ($x_player->p_pieces[$x_counter]->m_get_location() * $this->g_dievalue), FALSE);
        }
    }


    //now for each potential location see if there is a match in magic numbers
    
    
    for($x_counter_m = 1;$x_counter_m <=4;$x_counter_m++)
    {
        $x_temp_forecast = $x_forecast_pointers[$x_counter_m][1];
        
        if(in_array($x_temp_forecast,$x_magicnumbers))
        {
                //we have a potential magic number target
                $x_forecast_pointers[$x_counter_m][2] = TRUE;
                break;
        }
    }
    

    //clear the array of player's pieces that are not a likely hit
    $x_test_count_flag = FALSE;

    for($x_counter_test=1;$x_counter_test<=4;$x_counter_test++)
    {
        if($x_forecast_pointers[$x_counter_test][2] == TRUE) 
        {
            //found at least one
            $x_test_count_flag = TRUE;
            
        } 
        
        else 
        
        {
            unset($x_forecast_pointers[$x_counter_test]);
        }
    }


    //got at least one potential target
    if($x_test_count_flag) 
    {

        //now we have an array of only the possible markers to select to target
        $x_forecast_pointers = array_values($x_forecast_pointers);

        $x_temp_target = array_rand($x_forecast_pointers,1);

        //the piece we choose to move
        $x_piece_pointer = $x_forecast_pointers[$x_temp_target][0];


        $this->g_logmove( "<font color=darkred><bold>Player " . $x_player->p_get_playerid() . " is targeting ".$x_type." number <u>".$x_forecast_pointers[$x_temp_target][1]."</u> with piece ".$x_piece_pointer."</bold></font>");

        $this->g_marker_move($x_player, $x_piece_pointer);

        $x_found_target = TRUE;

    } 
    else
    {
       // $this->g_logmove("<font color=black>Player ".$x_player->p_get_playerid()." could not target a ".$x_type." number in this move.</font>");
        
        $x_found_target = FALSE;
    }

   
   return ($x_found_target);
   
} //END OF SET B







public function g_target_potential_clashes_set_C (Player $x_player) {

    //properties
    $x_forecast_pointers = array();
    $x_found_target = FALSE;
    $x_selection_pointers = array();
    $x_piece_pointer = 0;
    $x_temp_branch = 2;
    $x_temp_target = array();
    
    //populate current players positions and status to temp array
    for($x_counter = 1;$x_counter <= 4; $x_counter++)
    {
        
        if(!$x_player->p_pieces[$x_counter]->m_get_status()) 
        {
            //this piece is out of play - set up a dummy which will never get hit
        
            $x_forecast_pointers[$x_counter] = array($x_counter,999,FALSE);
            
            
        } 
        
        else
        
        {
            //otherwise, put in a forecast of where it would land based on the dice roll
         
            $x_forecast_pointers[$x_counter] = array($x_counter, $x_player->p_pieces[$x_counter]->m_get_location() * $this->g_dievalue, FALSE);
        }
    }


    //now for each potential location see if there is a match in the opponent's pieces
    
    $x_temp_opp = $this->g_return_other_player();
     
    for($x_counter_o = 1;$x_counter_o <= 4; $x_counter_o++)
    {
       
        if(!$x_temp_opp->p_pieces[$x_counter_o]->m_get_status()) 
        { 
            //opp position is out of play and should be ignored -  dummy value
            $this->g_logmove("Ignoring target piece ".$x_counter_o." as it is out of play.");
          
            $x_temp_opp_location = 888;
        }
        
        else
        
        {
            //hold the location of a potential target piece to hit
            $x_temp_opp_location = $x_temp_opp->p_pieces[$x_counter_o]->m_get_location();
        }

        for($x_counter_p=1;$x_counter_p<=4;$x_counter_p++)
        {
            //check that the locations match and that the opponents piece  is not at the start, or inactive:
            
            if($x_forecast_pointers[$x_counter_p][1] == $x_temp_opp_location && $x_temp_opp_location != 1 && $x_temp_opp_location != 888) 
          
            {
                //we have a potential target
                $x_forecast_pointers[$x_counter_p][2] = TRUE;
       
                $this->g_logmove( "<font color=green>Player " . $x_temp_opp->p_get_playerid() . "'s marker ".$x_counter_o.  " at location ".$x_temp_opp->p_pieces[$x_counter_o]->m_get_location()." is a target of piece ".$x_counter_p."</font>");
           
                break; //we only need to know this once         
           
          } 
            
        } //move on to next player piece

    } //move on to next opponent piece
    

    //clear the array of player's pieces that are not a likely hit
    $x_test_count_flag = FALSE;

    for($x_counter_test=1;$x_counter_test<=4;$x_counter_test++)
    {
        if($x_forecast_pointers[$x_counter_test][2] == TRUE) 
        {
            //found at least one
            $x_test_count_flag = TRUE;
            
        } 
        
        else 
        
        {
            unset($x_forecast_pointers[$x_counter_test]);
        }
    }


    //got at least one potential target
    if($x_test_count_flag) 
    {

        //now we have an array of only the possible markers to select to target
        $x_forecast_pointers = array_values($x_forecast_pointers);

        $x_temp_target = array_rand($x_forecast_pointers,1);

        //the piece we choose to move
        $x_piece_pointer = $x_forecast_pointers[$x_temp_target][0];


        $this->g_logmove( "<font color=green>Player " . $x_player->p_get_playerid() . " has ".sizeof($x_forecast_pointers)." targets to consider and chose to move piece ".$x_piece_pointer."</font>");

        $this->g_marker_move($x_player, $x_piece_pointer);

        $x_found_target = TRUE;

        return $x_found_target;    
    } 

    else

    {
        $this->g_logmove( "<font color=black>Player " . $x_player->p_get_playerid() . " could not find a clash target so is going to find a pointer at random to move.</font>");
    
        //toss up between on or off board
        $x_offboard_flag = FALSE;
        
        $x_onboard_flag = FALSE;
    
        for($x_counter=1;$x_counter<=4;$x_counter++)
        {
            //loop and see if there is a mix of on-board or off-board marker; do a coin toss if there is
            if($x_player->p_pieces[$x_counter]->m_get_location() == 1 && $x_player->p_pieces[$x_counter]->m_get_status()) 
            {
                $x_offboard_flag = TRUE;
            }    
        
            if($x_player->p_pieces[$x_counter]->m_get_location() > 1 && $x_player->p_pieces[$x_counter]->m_get_status()) 
            {
                $x_onboard_flag = TRUE;
            }    
        
        }
    
    if($x_onboard_flag) 
    {
    
        if($x_offboard_flag) 
        {
    
            //choice is a wonderful thing - 1 is onboard, 2 is offboard
            $x_temp_branch = RAND(1,2);
    
        } else
        
            $x_temp_branch = 1;

        }
    
        if($x_temp_branch == 1)
        {
        
            $x_piece_pointer = 0;
            
            $this->g_logmove( "<font color=black>Finding a piece on the board.</font>");
    
                $x_piece_pointer = $this->g_find_to_move_in_play($x_player);

                if($x_piece_pointer != 0) 
                {
                    //found one... move it        

                    $this->g_marker_move($x_player, $x_piece_pointer);

                    $x_found_target = TRUE;
                    
                } 
                
                else
                
                {
                    
                    //didn't find one, have to force to go down the SET D path.
                
                    $x_temp_branch = 0;
                
                    $x_found_target = FALSE;
                }
        }
    
        if($x_temp_branch == 0) 
        {
    
            $x_found_target = FALSE; 
    
            $this->g_logmove( "<font color=black>Finding a piece OFF the board using SET D.</font>");
        }
    
        return $x_found_target;
    }

    
} //END OF SET C


public function g_move_onto_board_set_D (Player $x_player) 
{
    
    $x_piece_pointer = $this->g_find_to_move_onto_board($x_player);

    if($x_piece_pointer == 0) 
    {

        //no more pieces to move on to the board
        $this->g_logmove( "<font color=orange>Player " . $x_player->p_get_playerid() . " did not have pieces off the board to move into play.</font>");
  
        return FALSE;
     
    }
    
    else
    
    {

        $this->g_marker_move($x_player, $x_piece_pointer);

        return TRUE;
    }    
}


protected function g_marker_move(Player $x_player,  $x_piece_pointer) 
{

        $x_old_position = $x_player->p_pieces[$x_piece_pointer]->m_get_location();
    
        if($this->g_dievalue != 0)
        {
            $x_new_location = $x_player->p_pieces[$x_piece_pointer]->m_calclocation($this->g_dievalue);
            
        }
        
        else
        
        {
            //Piece has been bumped to the start either due to clash or blowout
            $x_new_location = 1;
        }
       
        if($x_new_location > 120)
        {
            //blowout!
            $x_logstring = "<font color=red>Player " . $x_player->p_get_playerid($x_player) . " busted piece ".$x_piece_pointer." to a value of ".$x_new_location."!</font>";
                
            $this->g_logmove( $x_logstring );

            $x_new_location = 1;
            
        }
       
        $x_player->p_pieces[$x_piece_pointer]->m_setlocation($x_new_location);

        $x_logstring = "<font color=orange><b>[[[Player " . $x_player->p_get_playerid($x_player) . " moved piece ";
        $x_logstring .= $x_piece_pointer . " from position ".$x_old_position." to ";
        $x_logstring .=  $x_player->p_pieces[$x_piece_pointer]->m_get_location()."]]]</b></font>";

        $this->g_logmove( $x_logstring );

        //call clash detect unless it has moved to 1
        
        if($x_new_location == 120)
        {
            //piece has made it to the end and will be disabled
        
            $x_logstring = "<font color=teal><b>Player " . $x_player->p_get_playerid($x_player) . "s piece ";
            $x_logstring .= $x_piece_pointer . " has reached position ";
            $x_logstring .=  $x_player->p_pieces[$x_piece_pointer]->m_get_location(). " successfully!</b></font>";

            $this->g_logmove( $x_logstring );
    
            if ($x_player->p_pieces[$x_piece_pointer]->m_get_status()) 
            {
                $x_text = "ACTIVE"; 
                
            } 
            
            else
            
            { 
                $x_text = "INACTIVE"; 
            
            }
        
            $this->g_logmove( "<font color=teal>This piece is now set to: ".$x_text."</font>");

        }
        
        
        if($x_new_location != 1 && $x_new_location != 120)
        {
          
            $x_clash_result = $this->g_detect_clash($x_player->p_pieces[$x_piece_pointer]->m_get_location(), $this->g_return_other_player());
        }
        
            $this->g_gameover = $x_player->p_check_game_status($x_player);           
    }

        

} //end of GAME class


?>
