<?php


include_once 'class_definition_marker.php';
include_once 'class_definition_player.php';
include_once 'class_definition_game.php';


$test = new Game();

$test->g_newgame();


for ($x_counter = 1;$x_counter <= 1000;$x_counter++) {

$test->g_logmove( "<b><u>Game Move: ".$x_counter."</u></b>" );
    

$gameover = $test->g_player_action($test->g_return_player($test->g_get_playerturn()));


if($gameover) {
    
    $x_counter = 999999;
    
  
    
}

//$roll is a boolean indicating if a successful action was called? maybe?

/**
echo "<br>";
echo "- - - - - - - - - - - - - - - - - - - - - - <br>";

echo "POSITIONS AFTER MOVE: ".$x_counter."<br><br>";

for ($xx = 1; $xx<=2; $xx++) {
    
    for($yy = 1; $yy<=4; $yy++) {
        
        echo "Player ".$xx." Piece ".$yy." is at location: ";
        
        echo $test->g_players[$xx]->p_pieces[$yy]->m_location;
        
        echo "<br>";

    }
    
    echo "<br>";
}
   
echo "- - - - - - - - - - - - - - - - - - - - - - <br>";
**/ 
$test->g_flip_player();

}


echo "<br>";
echo "- - - - - - - - - - - - - - - - - - - - - - <br>";

echo "POSITIONS AT END OF GAME <br><br>";

for ($xx = 1; $xx<=2; $xx++) {
    
    for($yy = 1; $yy<=4; $yy++) {
        
        echo "Player ".$xx." Piece ".$yy." is at location: ";
        
        echo $test->g_players[$xx]->p_pieces[$yy]->m_location;
        
        echo "<br>";
      
        
    }
}
   
echo "- - - - - - - - - - - - - - - - - - - - - - <br>";


?>

