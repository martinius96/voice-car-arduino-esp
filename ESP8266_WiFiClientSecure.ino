/*|-------------------------------------------------|*/
/*| RC CAR VOICE CONTROL VIA WEB INTERFACE          |*/
/*| Webkit (GOOGLE) technology for voice-to-text    |*/
/*| Author: Martin Chlebovec (martinius96)          |*/
/*| LANGUAGE English - region US --> en-US          |*/
/*| Revison: 21. Jan. 2020                          |*/
/*| Arduino Core 2.5.0/2.5.2 compatible             |*/
/*|-------------------------------------------------|*/
#include <ESP8266WiFi.h>
#include <WiFiClientSecure.h>
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
