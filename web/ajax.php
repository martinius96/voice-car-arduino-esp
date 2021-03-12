<?php 
$preklad = $_POST['preklad'];
$preklad = strtolower($preklad);
$preklad = htmlspecialchars($preklad);
$preklad = trim( $preklad );
  if($preklad != ""){
    if ($preklad=="move forward"){     
        file_put_contents("translation.txt", 'UP');
    }
    else if ($preklad=="move backward"){     
        file_put_contents("translation.txt", 'DOWN');
    }else if ($preklad=="turn left"){     
        file_put_contents("translation.txt", 'LEFT');
     }else if ($preklad=="turn right"){     
        file_put_contents("translation.txt", 'RIGHT');
     }	
  }
?>
