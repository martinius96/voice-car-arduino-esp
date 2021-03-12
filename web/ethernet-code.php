<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voice control for car via Ethernet W5100</title>
  <meta name="description" content="Voice control system for RC car">
  <meta name="keywords" content="voice-to-text, arduino, ethernet, rc, car, voice, control">
  <meta name="author" content="Martin Chlebovec">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
            <li class="nav-item">
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
            <li class="nav-item active">
              <a class="nav-link" href="ethernet-code.php">Arduino + Ethernet W5100 code</a>
            </li>
          </ul>        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
     
<pre>
/*|-------------------------------------------------|*/
/*| RC CAR VOICE CONTROL VIA WEB INTERFACE          |*/
/*| Webkit (GOOGLE) technology for voice-to-text    |*/
/*| Author: Martin Chlebovec (martinius96)          |*/
/*| LANGUAGE English - region US --> en-US          |*/
/*| Revision: 21. Jan. 2020                         |*/
/*|-------------------------------------------------|*/
#include &lt;SPI.h>
#include &lt;Ethernet.h>

byte mac[] = { 0xAA, 0xBB, 0xCC, 0x81, 0x7B, 0x4A }; //fyzicka adresa MAC
char* serverName = "www.arduino.php5.sk"; // webserver

EthernetClient client;
void setup() {
  Serial.begin(115200);
  if (Ethernet.begin(mac) == 0) {
    Serial.println("DHCP nepridelilo adresu, skusam so statickou...");
    Ethernet.begin(mac);
  }
  Serial.print("  DHCP assigned IP ");
  Serial.println(Ethernet.localIP());
}
void executed_command() {
  client.stop();
  if (client.connect(serverName, 80)) {
    client.println("GET /php_car/command_executed.php HTTP/1.0");
    client.println("Host: www.arduino.php5.sk");
    client.println("Connection: close");
    client.println();
  } else {
    Serial.println("Connection failed");
  }
  client.stop();
}
void loop() {
  if (Ethernet.begin(mac) == 0) {
    Serial.println("DHCP nepridelilo adresu, skusam so statickou...");
    Ethernet.begin(mac);
  }
  if (client.connect(serverName, 80)) {
    client.println("GET /php_car/translation.txt HTTP/1.0");
    client.println("Host: www.arduino.php5.sk");
    client.println("Connection: close");
    client.println();
    while (client.connected()) {
      String hlavicka = client.readStringUntil('\n');
      Serial.println(hlavicka);
      if (hlavicka == "\r") {
        break;
      }
    }
    String line = client.readStringUntil('\n');
    Serial.println("Command is:");
    Serial.println(line);
    if (line == "UP") { //if command == go UP
      Serial.println("Moving forward...");
      // PUT CODE FOR MOVE FORWARD THERE
      executed_command();
    } else if (line == "DOWN") { //if command == go DOWN
      Serial.println("Moving backward...");
      // PUT CODE FOR MOVE BACKWARD THERE
      executed_command();
    } else if (line == "LEFT") { //if command == TURN LEFT
      Serial.println("Turning left...");
      // PUT CODE FOR TURN LEFT THERE
      executed_command();
    } else if (line == "RIGHT") { //if command == TURN RIGHT
      Serial.println("Turning right...");
      // PUT CODE FOR TURN RIGHT THERE
      executed_command();
    } else if (line == "Last command executed by ESP8266. Waiting for new command.") { //if command == NO_MORE_COMMAND
      Serial.println("No voice command set yet");
    } else {

      Serial.println("This command is not suported");
      executed_command();
    }
  }
  else {
    Serial.println("Connection failed");
    Serial.println();
  }
  client.stop();
  delay(5000);
}
</pre>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

