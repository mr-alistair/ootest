<?php

Class Player {
  //properties

  public $p_playerid;
  public $p_pieces = array();


  //methods

  function __construct( $x_id ) {
    $this->p_playerid = $x_id;

    for ( $x_counter = 1; $x_counter <= 4; $x_counter++ ) {
      $this->p_pieces[ $x_counter ] = $this->p_assignpiece( $this );

    }

  }

  //feed an array of markers per player
  public function p_assignpiece( Player $x_player ): Marker {
    $p_m_temp = new Marker( $x_player );
    return ( $p_m_temp );
  }

  public function p_get_playerid() {
    return $this->p_playerid;
  }

  public function p_check_game_status( Player $x_player ) {
    
    $x_game_over = TRUE;
    

    for($x_counter = 1;$x_counter<=4;$x_counter++)
    {
        if($x_player->p_pieces[$x_counter]->m_get_location() != 120)
        {
            $x_game_over = FALSE;
            
        }
    }

    return $x_game_over;
  }


}


?>
