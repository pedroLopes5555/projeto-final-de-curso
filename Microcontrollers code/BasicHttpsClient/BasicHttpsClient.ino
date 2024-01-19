
/**
   BasicHTTPSClient.ino

    Created on: 14.10.2018

*/
// Include the libraries we need
#include <OneWire.h>
#include <DallasTemperature.h>
#include <Arduino.h>
#include <WiFi.h>
#include <WiFiMulti.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>

#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into port 2 on the Arduino
#define ONE_WIRE_BUS 5

// root certeficate for the https 
// 
// 
const char* rootCACertificate = \
"-----BEGIN CERTIFICATE-----\n" \
"MIIDjjCCAnagAwIBAgIQAzrx5qcRqaC7KGSxHQn65TANBgkqhkiG9w0BAQsFADBh\n" \
"MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3\n" \
"d3cuZGlnaWNlcnQuY29tMSAwHgYDVQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBH\n" \
"MjAeFw0xMzA4MDExMjAwMDBaFw0zODAxMTUxMjAwMDBaMGExCzAJBgNVBAYTAlVT\n" \
"MRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTEHd3dy5kaWdpY2VydC5j\n" \
"b20xIDAeBgNVBAMTF0RpZ2lDZXJ0IEdsb2JhbCBSb290IEcyMIIBIjANBgkqhkiG\n" \
"9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuzfNNNx7a8myaJCtSnX/RrohCgiN9RlUyfuI\n" \
"2/Ou8jqJkTx65qsGGmvPrC3oXgkkRLpimn7Wo6h+4FR1IAWsULecYxpsMNzaHxmx\n" \
"1x7e/dfgy5SDN67sH0NO3Xss0r0upS/kqbitOtSZpLYl6ZtrAGCSYP9PIUkY92eQ\n" \
"q2EGnI/yuum06ZIya7XzV+hdG82MHauVBJVJ8zUtluNJbd134/tJS7SsVQepj5Wz\n" \
"tCO7TG1F8PapspUwtP1MVYwnSlcUfIKdzXOS0xZKBgyMUNGPHgm+F6HmIcr9g+UQ\n" \
"vIOlCsRnKPZzFBQ9RnbDhxSJITRNrw9FDKZJobq7nMWxM4MphQIDAQABo0IwQDAP\n" \
"BgNVHRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBhjAdBgNVHQ4EFgQUTiJUIBiV\n" \
"5uNu5g/6+rkS7QYXjzkwDQYJKoZIhvcNAQELBQADggEBAGBnKJRvDkhj6zHd6mcY\n" \
"1Yl9PMWLSn/pvtsrF9+wX3N3KjITOYFnQoQj8kVnNeyIv/iPsGEMNKSuIEyExtv4\n" \
"NeF22d+mQrvHRAiGfzZ0JFrabA0UWTW98kndth/Jsw1HKj2ZL7tcu7XUIOGZX1NG\n" \
"Fdtom/DzMNU+MeKNhJ7jitralj41E6Vf8PlwUHBHQRFXGU7Aj64GxJUTFy8bJZ91\n" \
"8rGOmaFvE7FBcf6IKshPECBV1/MUReXgRPTqh5Uykw7+U0b6LJ3/iyK5S9kJRaTe\n" \
"pLiaWN0bfVKfjllDiIGknibVb63dDcY3fe0Dkhvld1927jyNxF1WW6LZZm6zNTfl\n" \
"MrY=\n"
"-----END CERTIFICATE-----\n";


WiFiMulti WiFiMulti;


// Setup a oneWire instance to communicate with any OneWire devices (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);


//const int tdsSensorPin = A0; // Define the analog pin for the TDS sensor
int AcidSolution = D3;
const int phSensor = A2;


namespace device {
  float aref = 4.3;
}
namespace pin {
  const byte tds_sensor = A0;
}

namespace sensor{
  float ec = 0;
  unsigned int tds = 0;
  float waterTemperature = 0;
  float ecCalibration = 1;
}






String readPh()
{
  float pHValue = analogRead(phSensor);
  float voltage = pHValue * (3.3/1023.0);
  return String((voltage*4.24));
}

String readTemperature(){
    // call sensors.requestTemperatures() to issue a global temperature 
  // request to all devices on the bus
  Serial.print("Requesting temperatures...");
  sensors.requestTemperatures(); // Send the command to get temperatures
  Serial.println("DONE");
  // After we got the temperatures, we can print them here.
  // We use the function ByIndex, and as an example get the temperature from the first sensor only.
  float tempC = sensors.getTempCByIndex(0);

  // Check if reading was successful
  if(tempC != DEVICE_DISCONNECTED_C) 
  {
    Serial.print("Temperature for the device 1 (index 0) is: ");
    Serial.println(tempC);
    return String(tempC);
  } 
  else
  {
    Serial.println("Error: Could not read temperature data");
    return "error";
  }
}



float readTdsValue(){

  sensor::waterTemperature = 20.0;

  float rawEc = analogRead(pin::tds_sensor) * device::aref/ 4095.0;

  float temperatureCoefecient = 1.0 + 0.02 * (sensor::waterTemperature - 25.0);
  sensor::ec = (rawEc/temperatureCoefecient) * sensor::ecCalibration;
  sensor::tds = (133.42 * pow(sensor::ec, 3) - 255.86 * sensor::ec * sensor::ec + 857.39 * sensor::ec) * 0.5;
  Serial.print(F("TDS:")); Serial.println(sensor::tds);
  Serial.print(F("EC:")); Serial.println(sensor::ec, 2);
  Serial.print(F("uS:")); Serial.println(sensor::ec * 640);
  Serial.print(F("Temperature:")); Serial.println(sensor::waterTemperature,2);
  return sensor::ec * 640;

}


// Not sure if WiFiClientSecure checks the validity date of the certificate. 
// Setting clock just to be sure...
void setClock() {
  configTime(0, 0, "pool.ntp.org");

  Serial.print(F("Waiting for NTP time sync: "));
  time_t nowSecs = time(nullptr);
  while (nowSecs < 8 * 3600 * 2) {
    delay(500);
    Serial.print(F("."));
    yield();
    nowSecs = time(nullptr);
  }

  Serial.println();
  struct tm timeinfo;
  gmtime_r(&nowSecs, &timeinfo);
  Serial.print(F("Current time: "));
  Serial.print(asctime(&timeinfo));
}



void setup() {
  
  pinMode(phSensor, INPUT);

  Serial.begin(115200);
  
  // Serial.setDebugOutput(true);
  sensors.begin();
  Serial.println();
  Serial.println();
  Serial.println();

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("LL-GUEST", "VendaDoPinheiro");

  // wait for WiFi connection
  Serial.print("Waiting for WiFi to connect...");
  while ((WiFiMulti.run() != WL_CONNECTED)) {
    Serial.print(".");
  }
  Serial.println(" connected");

  setClock();  
}



void sendValueToApi(String type, String value){
    WiFiClientSecure *client = new WiFiClientSecure;
  if(client) {
    client -> setCACert(rootCACertificate);

    {
      // Add a scoping block for HTTPClient https to make sure it is destroyed before WiFiClientSecure *client is 
      HTTPClient https;
  
      Serial.print("[HTTPS] begin...\n");
      if (https.begin(*client, "https://hydrogrowthmanager.azurewebsites.net/automation/ReciveSensorData")) {  // HTTPS
        Serial.print("[HTTPS] GET...\n");
        // start connection and send HTTP header2
        https.addHeader("Content-Type", "application/json");
        int httpCode = https.POST("{\"micrcocontrollerID\":\"" + WiFi.macAddress() + "\",\"type\":\"" + type + "\",\"value\":\"" + value +"\"}");
        Serial.print("post:");
        Serial.print("{\"micrcocontrollerID\":\"" + WiFi.macAddress() + "\",\"type\":\"" + type + "\",\"value\":\"" + value +"\"}");
        Serial.printf("[HTTPS] GET... code: %d\n", httpCode);

        // httpCode will be negative on error
        if (httpCode > 0) {
          // HTTP header has been send and Server response header has been handled
          
  
          // file found at server
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            String payload = https.getString();
            Serial.println(payload);
          }
        } else {
          Serial.printf("[HTTPS] GET... failed, error: %s\n", https.errorToString(httpCode).c_str());
        }
  
        https.end();
      } else {
        Serial.printf("[HTTPS] Unable to connect\n");
      }

      // End extra scoping block
    }
  
    delete client;
  } else {
    Serial.println("Unable to create client");
  }

  Serial.println();
  Serial.println("Waiting 10s before the next round...");
   
}

void loop() {




  String temperature = readTemperature();
  Serial.print("---------------------");
  Serial.print(temperature);
  Serial.print("---------------------");
  sendValueToApi("temperature", String(temperature));




  float tds = readTdsValue();
  Serial.print("---------------------");
  Serial.print(tds);
  Serial.print("---------------------");
  sendValueToApi("tds", String(tds));


  String ph = readPh();
  Serial.print("---------------------");
  Serial.print(ph);
  Serial.print("---------------------");
  sendValueToApi("ph", ph);




  /*
  if(tds< 500){
    digitalWrite(AcidSolution, HIGH); // Turn the digital output HIGH 
  }else{
    digitalWrite(AcidSolution, LOW); // Turn the digital output HIGH 

  }
*/



  delay(10000);
}
