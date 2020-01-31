/*|-------------------------------------------------|*/
/*| RC CAR VOICE CONTROL VIA WEB INTERFACE          |*/
/*| Webkit (GOOGLE) technology for voice-to-text    |*/
/*| Author: Martin Chlebovec (martinius96)          |*/
/*| LANGUAGE English - region US --> en-US          |*/
/*| Revision: 21. Jan. 2020                         |*/
/*|-------------------------------------------------|*/
#include <SPI.h>
#include <Ethernet.h>

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
