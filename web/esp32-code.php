<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voice control for car via ESP32</title>
  <meta name="description" content="Voice control system for RC car">
  <meta name="keywords" content="voice-to-text, arduino, esp32, rc, car, voice, control">
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
             <li class="nav-item">
              <a class="nav-link" href="esp8266-code.php">ESP8266 code</a>
            </li>
             <li class="nav-item active">
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
/*| Revision: 22. Jan. 2020                         |*/
/*| Arduino Core 1.0.1+                             |*/
/*|-------------------------------------------------|*/
#include &lt;WiFi.h>
#include &lt;WiFiClientSecure.h>
const char* ssid     =    "WIFI_NAME";
const char* pass     =    "WIFI_PASS";
const char* host     =    "arduino.php5.sk";
const int httpsPort = 443; //http port
const char* test_root_ca = \
                           "-----BEGIN CERTIFICATE-----\n" \
                           "MIIEsTCCA5mgAwIBAgIQCKWiRs1LXIyD1wK0u6tTSTANBgkqhkiG9w0BAQsFADBh\n" \
                           "MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3\n" \
                           "d3cuZGlnaWNlcnQuY29tMSAwHgYDVQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBD\n" \
                           "QTAeFw0xNzExMDYxMjIzMzNaFw0yNzExMDYxMjIzMzNaMF4xCzAJBgNVBAYTAlVT\n" \
                           "MRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTEHd3dy5kaWdpY2VydC5j\n" \
                           "b20xHTAbBgNVBAMTFFJhcGlkU1NMIFJTQSBDQSAyMDE4MIIBIjANBgkqhkiG9w0B\n" \
                           "AQEFAAOCAQ8AMIIBCgKCAQEA5S2oihEo9nnpezoziDtx4WWLLCll/e0t1EYemE5n\n" \
                           "+MgP5viaHLy+VpHP+ndX5D18INIuuAV8wFq26KF5U0WNIZiQp6mLtIWjUeWDPA28\n" \
                           "OeyhTlj9TLk2beytbtFU6ypbpWUltmvY5V8ngspC7nFRNCjpfnDED2kRyJzO8yoK\n" \
                           "MFz4J4JE8N7NA1uJwUEFMUvHLs0scLoPZkKcewIRm1RV2AxmFQxJkdf7YN9Pckki\n" \
                           "f2Xgm3b48BZn0zf0qXsSeGu84ua9gwzjzI7tbTBjayTpT+/XpWuBVv6fvarI6bik\n" \
                           "KB859OSGQuw73XXgeuFwEPHTIRoUtkzu3/EQ+LtwznkkdQIDAQABo4IBZjCCAWIw\n" \
                           "HQYDVR0OBBYEFFPKF1n8a8ADIS8aruSqqByCVtp1MB8GA1UdIwQYMBaAFAPeUDVW\n" \
                           "0Uy7ZvCj4hsbw5eyPdFVMA4GA1UdDwEB/wQEAwIBhjAdBgNVHSUEFjAUBggrBgEF\n" \
                           "BQcDAQYIKwYBBQUHAwIwEgYDVR0TAQH/BAgwBgEB/wIBADA0BggrBgEFBQcBAQQo\n" \
                           "MCYwJAYIKwYBBQUHMAGGGGh0dHA6Ly9vY3NwLmRpZ2ljZXJ0LmNvbTBCBgNVHR8E\n" \
                           "OzA5MDegNaAzhjFodHRwOi8vY3JsMy5kaWdpY2VydC5jb20vRGlnaUNlcnRHbG9i\n" \
                           "YWxSb290Q0EuY3JsMGMGA1UdIARcMFowNwYJYIZIAYb9bAECMCowKAYIKwYBBQUH\n" \
                           "AgEWHGh0dHBzOi8vd3d3LmRpZ2ljZXJ0LmNvbS9DUFMwCwYJYIZIAYb9bAEBMAgG\n" \
                           "BmeBDAECATAIBgZngQwBAgIwDQYJKoZIhvcNAQELBQADggEBAH4jx/LKNW5ZklFc\n" \
                           "YWs8Ejbm0nyzKeZC2KOVYR7P8gevKyslWm4Xo4BSzKr235FsJ4aFt6yAiv1eY0tZ\n" \
                           "/ZN18bOGSGStoEc/JE4ocIzr8P5Mg11kRYHbmgYnr1Rxeki5mSeb39DGxTpJD4kG\n" \
                           "hs5lXNoo4conUiiJwKaqH7vh2baryd8pMISag83JUqyVGc2tWPpO0329/CWq2kry\n" \
                           "qv66OSMjwulUz0dXf4OHQasR7CNfIr+4KScc6ABlQ5RDF86PGeE6kdwSQkFiB/cQ\n" \
                           "ysNyq0jEDQTkfa2pjmuWtMCNbBnhFXBYejfubIhaUbEv2FOQB3dCav+FPg5eEveX\n" \
                           "TVyMnGo=\n" \
                           "-----END CERTIFICATE-----\n";
WiFiClientSecure client;
void setup() {
  Serial.begin(115200);
  delay(10);
  Serial.print("Connecting to ssid: ");
  Serial.println(ssid);
  WiFi.disconnect(true);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, pass);
  while (WiFi.waitForConnectResult() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  client.setCACert(test_root_ca);
  Serial.println("");
  Serial.println("WiFi uspesne pripojene");
  Serial.println("IP adresa: ");
  Serial.println(WiFi.localIP());
  Serial.println("Ready");
}

void translation() {
  if (client.connect(host, httpsPort)) {
    String url = "/php_car/translation.txt";
    client.print(String("GET ") + url + " HTTP/1.0\r\n" + "Host: " + host + "\r\n" + "User-Agent: ESP32\r\n" + "Connection: close\r\n\r\n");
    while (client.connected()) {
      String hlavicka = client.readStringUntil('\n');
      if (hlavicka == "\r") {
        break;
      }
    }
    String line = client.readStringUntil('\n');
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
      client.stop();
      executed_command();
      // PUT CODE FOR TURN RIGHT THERE
    } else if (line == "Last command executed by ESP8266. Waiting for new command.") { //if command == NO_MORE_COMMAND
      Serial.println("No voice command set yet");
    }
  } else {
    Serial.println("Problem connecting to GET voice command");
  }
  client.stop();
}

void executed_command() {
  if (client.connect(host, httpsPort)) {
    Serial.println("RESET VOICE COMMAND SUCESSFUL");
    String url = "/php_car/command_executed.php";
    client.print(String("GET ") + url + " HTTP/1.0\r\n" + "Host: " + host + "\r\n" + "User-Agent: ESP32\r\n" + "Connection: close\r\n\r\n");
  } else {
    Serial.println("CONNECTION FAILED");
  }
  client.stop();
}

void loop() {
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");

  }
  translation();
  delay(2000); // WAIT () miliseconds before next reading of variable
}
</pre>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

