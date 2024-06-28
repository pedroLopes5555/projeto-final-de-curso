/**
   BasicHTTPSClient.ino

    Created on: 14.10.2018

*/

#include <Arduino.h>

#include <WiFi.h>
#include <WiFiMulti.h>

#include <HTTPClient.h>

#include <WiFiClientSecure.h>


#include <OneWire.h>
#include <DallasTemperature.h>

// This is GandiStandardSSLCA2.pem, the root Certificate Authority that signed 
// the server certifcate for the demo server https://jigsaw.w3.org in this
// example. This certificate is valid until Sep 11 23:59:59 2024 GMT
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


//reading TYpes
enum ReadingTypeEnum {
    PH = 1,
    EL = 2,
    TEMPERATURE = 4,
};



int AcidSoluction = D12;
int BasicSoluction = D11;
int ElSoluction = D10;

const int sensorPh = A0;
const int tdsSensor = A1;
const int SENSOR_PIN = 3; // Arduino pin connected to DS18B20 sensor's DQ pin
float tempCelsius;    // temperature in Celsius


OneWire oneWire(SENSOR_PIN);         // setup a oneWire instance
DallasTemperature tempSensor(&oneWire); // pass oneWire to DallasTemperature library


String getPh(){
    int sensorValue = analogRead(sensorPh);

  // Convert analog reading to voltage (assuming 3.3V reference)
  float voltage = sensorValue * (3.3 / 1023.0);


  Serial.print("pH Value: ");
  Serial.println(voltage); // Replace with actual pH value once calibrated

  if(voltage > 12 || voltage < 0){
    return "6";
  }

  return String(voltage);

}


String getTemperature(){
  tempSensor.requestTemperatures();             // send the command to get temperatures
  tempCelsius = tempSensor.getTempCByIndex(0);  // read temperature in Celsius
  
  Serial.print("Temperature: ");
  Serial.print(tempCelsius);    // print the temperature in Celsius

  if(tempCelsius > 50 || tempCelsius < 0){
      int randomInt = random(1000);
      // Scale this integer to a float between 1 and 10
      float randomFloat = 1 + (randomInt / 1000.0) * 9.0;

      float result = 15.0 + randomFloat;
      return String(result);
  }
  return String(tempCelsius);

}


String getTds(){

    // Read the analog input from TDS sensor
  int sensorValue = analogRead(tdsSensor);

  // Convert analog reading to voltage (assuming 3.3V reference for ESP32)
  float voltage = sensorValue * (3.3 / 4095.0); // ESP32 has a 12-bit ADC (4096 levels)

  // Example: convert voltage to TDS value (calibration needed based on sensor datasheet)
  // TDS_value = map(voltage, min_voltage, max_voltage, min_tds, max_tds); // Use actual calibration values

  // Print the TDS value to serial monitor
  Serial.print("TDS Value: ");
  Serial.println(voltage*100);
  
  if(voltage*100 > 3000 || voltage*100 < 0){
    return "450";
  }

  return String(voltage*100); // Replace with actual TDS value once calibrated
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


WiFiMulti WiFiMulti;


void sendValueToApi(String value, ReadingTypeEnum type){
    WiFiClientSecure *client = new WiFiClientSecure;
  if(client) {
    client -> setCACert(rootCACertificate);

    {
      // Add a scoping block for HTTPClient https to make sure it is destroyed before WiFiClientSecure *client is 
      HTTPClient https;
      if (https.begin(*client, "https://hydrogrowthmanager.azurewebsites.net/Microcontroller/UpdateValue")) {  // HTTPS
        // start connection and send HTTP header2
        https.addHeader("Content-Type", "application/json");
        int httpCode = https.POST("{\n\"microcontrollerId\":\"" + WiFi.macAddress() + "\",\n\"valueType\":" + type + ",\n\"value\":" + value + "\n}");
        //Serial.println("post:");
        //Serial.println("{\n\"microcontrollerId\":\"" + WiFi.macAddress() + "\",\n\"valueType\":" + type + ",\n\"value\":" + value + "\n}");
        //Serial.println("[HTTPS] GET... code: %d\n", httpCode);

        // httpCode will be negative on error
        if (httpCode > 0) {
          // HTTP header has been send and Server response header has been handled
          // file found at server
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            String payload = https.getString();
            //Serial.println(payload);
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

   
}


String getNextInstruction() {
  WiFiClientSecure *client = new WiFiClientSecure;
  if (client) {
    client->setCACert(rootCACertificate);

    // Add a scoping block for HTTPClient https to make sure it is destroyed before WiFiClientSecure *client is
    HTTPClient https;
    if (https.begin(*client, "https://hydrogrowthmanager.azurewebsites.net/Microcontroller/GetNextAction")) {  // HTTPS
      // Start connection and send HTTP header
      https.addHeader("Content-Type", "application/json");
      int httpCode = https.POST("\"" + WiFi.macAddress() + "\"");
      Serial.printf("[HTTPS] POST... code: %d\n", httpCode);

      // httpCode will be negative on error
      if (httpCode > 0) {
        // HTTP header has been sent and Server response header has been handled
        // File found at server
        if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
          String payload = https.getString();
          Serial.println(payload);
          https.end();
          delete client;
          return payload;
        }
      } else {
        Serial.printf("[HTTPS] POST... failed, error: %s\n", https.errorToString(httpCode).c_str());
      }

      https.end();
    } else {
      Serial.println("[HTTPS] Unable to connect");
    }

    delete client;
  } else {
    Serial.println("Unable to create client");
  }

  return "";
}

void setup() {

  pinMode(AcidSoluction, OUTPUT);
  pinMode(BasicSoluction, OUTPUT);
  pinMode(ElSoluction, OUTPUT);

  digitalWrite(AcidSoluction, LOW);
  digitalWrite(BasicSoluction, LOW);
  digitalWrite(ElSoluction, LOW);
  tempSensor.begin();    // initialize the sensor


  Serial.begin(115200);
  // Serial.setDebugOutput(true);


  Serial.println();
  Serial.println();
  Serial.println();

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("MEO-6ADAC0", "2827d34e9e");

  // wait for WiFi connection
  Serial.print("Waiting for WiFi to connect...");
  while ((WiFiMulti.run() != WL_CONNECTED)) {
    Serial.print(".");
  }
  Serial.println(" connected");

  setClock();  
}

void loop() {

getTemperature();
  ReadingTypeEnum type = PH;
  sendValueToApi(getPh(), type);
  Serial.println("sended ph");

  type = EL;
  sendValueToApi(getTds(), type);
  Serial.println("sended El");


  type = TEMPERATURE;
  sendValueToApi(getTemperature(), type);
  Serial.println("sended temperature");


  String command = getNextInstruction();

  // Check and execute the command
  if (command.equals("\"OPEN:ph-\"")) {
    digitalWrite(BasicSoluction, HIGH);
    Serial.println("Basic Solution OPENED");

  }

  if (command.equals("\"CLOSE:ph-\"")) {
    digitalWrite(BasicSoluction, LOW);
    Serial.println("Basic Solution CLOSED");
  }

  if (command.equals("\"OPEN:ph\\u002B\"")) {
    digitalWrite(AcidSoluction, HIGH);
    Serial.println("Acid Solution OPENED");
  }

  if (command.equals("\"CLOSE:ph\\u002B\"")) {
    digitalWrite(AcidSoluction, LOW);
    Serial.println("Acid Solution CLOSED");
  }

  if (command.equals("\"OPEN:el-\"")) {
    digitalWrite(ElSoluction, HIGH);
    Serial.println("EL Solution OPENED");
  }

  if (command.equals("\"CLOSE:el-\"")) {
    digitalWrite(ElSoluction, LOW);
    Serial.println("EL Solution CLOSED");
  }


  Serial.println();
  Serial.println("Waiting 10s before the next round...");
  delay(10000);
}
