<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voice control for car via ESP8266</title>
  <meta name="description" content="Voice control system for RC car">
  <meta name="keywords" content="voice-to-text, arduino, esp8266, rc, car, voice, control">
  <meta name="author" content="Martin Chlebovec">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script type="text/javascript">
    window.smartlook||(function(d) {
    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
    c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
    })(document);
    smartlook('init', 'db50efe9fff280a17db52b82be221240cbbd3dbe');
</script>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-76290977-2"></script>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
  <style>
    body {
      padding-top: 54px;
    }
    @media (min-width: 992px) {
      body {
        padding-top: 56px;
      }
    }
    footer {   
position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
   background:#999;
}
  </style>
</head>
<body>
  <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">Voice controlled car</a>
         <div class="collapse navbar-collapse" id="navbarResponsive">
         <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Dashboard
                <span class="sr-only">(current)</span>
              </a>
            </li>         
             <li class="nav-item ">
              <a class="nav-link" href="esp8266-code.php">ESP8266 code</a>
            </li>
            <li class="nav-item ">
              <a class="nav-link" href="esp32-code.php">ESP32 code</a>
            </li>
            <li class="nav-item ">
              <a class="nav-link" href="ethernet-code.php">Arduino + Ethernet W5100 code</a>
            </li>
          </ul>        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
           <form id="labnol" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"><br>
      <img onclick="startDictation();" src="https://image.flaticon.com/icons/svg/820/820165.svg" height=256px width=256px alt="For voice control command click on microphone!">
    </form>
        </div>
      </div>
	   <div class="row">
	   <div class="col-lg-4">
          <h3 class="mt-5">Actual state</h3>
         <li id="active"></li>		      
        </div>
        
		 <div class="col-lg-4">
          <h3 class="mt-5">Commands:</h3>
         <li>Move forward</li>
		 <li>Move backward</li> 
     <li>Turn left</li>
     <li>Turn right</li>		   
        </div>
		 <div class="col-lg-4">
          <h3 class="mt-5">Instructions:</h3>
		  <li>Use Google Chrome and HTTPS connection!</li>
         <li>Allow your microphone in settings of browser.</li>
<li>Click on microphone and say your voice command.</li>
        </div>
      </div>
	  <center><footer style="background: #D35400;"><font color="white">Made by: </font><a href="https://www.facebook.com/martin.s.chlebovec">Martin Chlebovec</a> <font color="white">Technologies: Webkit, PHP, AJAX</font></footer></center>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
<script type="text/javascript">
    function startDictation() {

        if (window.hasOwnProperty('webkitSpeechRecognition')) {

            var recognition = new webkitSpeechRecognition();
            var result = '';

            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.lang = "en-US";
            recognition.start();

            recognition.onresult = function(e) {
                result = e.results[0][0].transcript;

                $.post(
                    "ajax.php",
                    { preklad: result }
                );

                recognition.stop();
            };

            recognition.onerror = function(e) {
                recognition.stop();
            }

        }
    }
</script>
</html>
<script>
       setInterval(function(){
    $.get('active.php', function(data){
        $('#active').html(data)
    });
},300);   
</script>
