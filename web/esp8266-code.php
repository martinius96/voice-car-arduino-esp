<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voice control for car via ESP8266</title>
  <meta name="description" content="Voice control system for RC car">
  <meta name="keywords" content="voice-to-text, arduino, esp8266, rc, car, voice, control">
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
             <li class="nav-item active">
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
     
<pre>
/*|-------------------------------------------------|*/
/*| RC CAR VOICE CONTROL VIA WEB INTERFACE          |*/
/*| Webkit (GOOGLE) technology for voice-to-text    |*/
/*| Author: Martin Chlebovec (martinius96)          |*/
/*| LANGUAGE English - region US --> en-US          |*/
/*| Revison: 21. Jan. 2020                          |*/
/*| Arduino Core 2.5.0/2.5.2 compatible             |*/
/*|-------------------------------------------------|*/
#include &lt;ESP8266WiFi.h>
#include &lt;WiFiClientSecure.h>
const char * ssid = "WIFI_NAME";
const char * password = "WIFI_PASS";
const char * host = "arduino.php5.sk"; //yourdomain
const int httpsPort = 443; //https port
const char fingerprint[] PROGMEM = "b0 6d 7f 8c 98 78 8e 6e 0a 57 a8 2f 7e d1 40 2a 1e 3f 48 f7";
void setup() {
  Serial.begin(115200);
  Serial.println();
  Serial.print("Connecting to WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}
void executed_command() {
  WiFiClientSecure client;
  Serial.printf("Using fingerprint '%s'\n", fingerprint);
  client.setFingerprint(fingerprint);
  if (client.connect(host, httpsPort)) {
    String url = "/php_car/command_executed.php";
    client.print(String("GET ") + url + " HTTP/1.0\r\n" + "Host: " + host + "\r\n" + "User-Agent: NodeMCU\r\n" + "Connection: close\r\n\r\n");
    Serial.println("Connection sucessful");
    Serial.println("Command executed to PHP service");
  } else {
    Serial.println("Error connecting to PHP service for verifying action");
  }
  client.stop();
}
void loop() {
  WiFiClientSecure client;
  Serial.printf("Using fingerprint '%s'\n", fingerprint);
  client.setFingerprint(fingerprint);
  if (client.connect(host, httpsPort)) {
    String url = "/php_car/translation.txt";
    client.print(String("GET ") + url + " HTTP/1.0\r\n" + "Host: " + host + "\r\n" + "User-Agent: NodeMCU\r\n" + "Connection: close\r\n\r\n");
    Serial.println("Connection sucessful");
    while (client.connected()) {
      String line = client.readStringUntil('\n');
      if (line == "\r") {
        break;
      }
    }
    String line = client.readStringUntil('\n');
    Serial.println("RC control command: ");
    Serial.println(line);
    if (line == "UP") { //if command == go UP
      Serial.println("Moving forward...");
      // PUT CODE FOR MOVE FORWARD THERE
      client.stop();
      executed_command();
    } else if (line == "DOWN") { //if command == go DOWN
      Serial.println("Moving backward...");
      // PUT CODE FOR MOVE BACKWARD THERE
      client.stop();
      executed_command();
    } else if (line == "LEFT") { //if command == TURN LEFT
      Serial.println("Turning left...");
      // PUT CODE FOR TURN LEFT THERE
      client.stop();
      executed_command();
    } else if (line == "RIGHT") { //if command == TURN RIGHT
      Serial.println("Turning right...");
      // PUT CODE FOR TURN RIGHT THERE
      client.stop();
      executed_command();
    } else if (line == "Last command executed by ESP8266. Waiting for new command.") { //if command == NO_MORE_COMMAND
      client.stop();
      Serial.println("No voice command set yet");
    } else {
      client.stop();
      Serial.println("This command is not suported");
      executed_command();
    }
  } else {
    Serial.println("Connection was not sucessful");
  }
  client.stop();
  delay(2000); // WAIT () miliseconds before next reading of variable
}
</pre>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

