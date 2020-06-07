<?php

Class Marker {
  //properties

  public $m_owner;
  public $m_location;

  //methods

  function __construct( Player $p_playerid ) {

    $this->m_owner = $p_playerid->p_get_playerid();
    $this->m_location = 1;

  }

  public function m_assignowner( $x_owner ) {
    $this->m_owner = $x_owner;

  }


  public function m_calclocation( $x_diceroll ) {
   
    $x_calcvalue = $this->m_location * $x_diceroll;

    return ( $x_calcvalue );
  }

  public function m_setlocation( $x_newlocation ) {
    $this->m_location = $x_newlocation;
  }

public function m_get_location() {
    return ($this->m_location);
}

public function m_get_status() {
    
    if($this->m_get_location() == 120)
    {
       return FALSE;
    }
    else
    {
        return TRUE;
    }
}


}

?>
