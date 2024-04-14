// namespace greenhouse.Models
// {
//     public enum ReadingTypeEnum
//     {
//         PH = 0x1,
//         EL = 0x1 << 1,
//         TEMPERATURE = 0x1 << 2,
//     }
// }


/*
Microcontroller/GetDesiredValue
{
    "microcontrollerId": "087be1ec-ef3a-414b-9db4-5ff789ce3fb3",
    "valueType": 1
}

Microcontroller/UpdateValue
{
    "microcontrollerId": "087be1ec-ef3a-414b-9db4-5ff789ce3fb3",
    "valueType": 2,
    "value": 3
}
*/





// Include the libraries we need
#include <OneWire.h>
#include <DallasTemperature.h>
#include <Arduino.h>
#include <WiFi.h>
#include <WiFiMulti.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>

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



int AcidSoluction = D10;
int ELSoluction = D9;

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


namespace DesiredValues{
  float ec = 100;
  float ph = 6;
  float temperature = 0;
}


enum ReadingTypeEnum {
    PH = 1,
    EL = 2,
    TEMPERATURE = 4,
};





String readPh()
{
  pH_Value = analogRead(phSensor); 
  Voltage = pH_Value * (3.3 / 4095.0); 
  Serial.println(Voltage); 
  return pH_Value;
}

String readTemperature(){
    // call sensors.requestTemperatures() to issue a global temperature 
  // request to all devices on the bus
  // Serial.print("Requesting temperatures...");
  // sensors.requestTemperatures(); // Send the command to get temperatures
  // Serial.println("DONE");
  // After we got the temperatures, we can print them here.
  // We use the function ByIndex, and as an example get the temperature from the first sensor only.
  float tempC = sensors.getTempCByIndex(0);

  // Check if reading was successful
  if(tempC != DEVICE_DISCONNECTED_C) 
  {
    //Serial.print("Temperature:");
    //Serial.println(tempC);
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
  // Serial.print(F("TDS:")); Serial.println(sensor::tds);
  // Serial.print(F("EC:")); Serial.println(sensor::ec, 2);
  // Serial.print(F("uS:")); Serial.println(sensor::ec * 640);
  // Serial.print(F("Temperature:")); Serial.println(sensor::waterTemperature,2);
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
  pinMode(AcidSoluction, OUTPUT);
  pinMode(ELSoluction, OUTPUT);

  Serial.begin(115200);
  
  // Serial.setDebugOutput(true);
  sensors.begin();
  Serial.println();
  Serial.println();
  Serial.println();

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("OS_VDF", "BemVind0s");

  // wait for WiFi connection
  Serial.print("Waiting for WiFi to connect...");
  while ((WiFiMulti.run() != WL_CONNECTED)) {
    Serial.print(".");
  }
  Serial.println(" connected");

  setClock();  
}



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
        Serial.println("post:");
        Serial.print("{\n\"microcontrollerId\":\"" + WiFi.macAddress() + "\",\n\"valueType\":" + type + ",\n\"value\":" + value + "\n}");
        Serial.printf("[HTTPS] GET... code: %d\n", httpCode);

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


String requestDesiredValues(ReadingTypeEnum type){
// Microcontroller/GetDesiredValue
// {
//     "microcontrollerId": "087be1ec-ef3a-414b-9db4-5ff789ce3fb3",
//     "valueType": 1
// }
  WiFiClientSecure *client = new WiFiClientSecure;

  if(client) {
    client -> setCACert(rootCACertificate);

    {
      // Add a scoping block for HTTPClient https to make sure it is destroyed before WiFiClientSecure *client is 
      HTTPClient https;
      if (https.begin(*client, "https://hydrogrowthmanager.azurewebsites.net/Microcontroller/GetDesiredValue")) {  // HTTPS
        // start connection and send HTTP header2
        https.addHeader("Content-Type", "application/json");
        int httpCode = https.POST("{\"microcontrollerId\":\"" + WiFi.macAddress() + "\",\"valueType\":" + String(type) + "}");

        //Serial.print("{\"microcontrollerId\":\"" + WiFi.macAddress() + "\",\"type\":" + String(type) + "}");
        //Serial.printf("[HTTPS] GET... code: %d\n", httpCode);

        // httpCode will be negative on error
        if (httpCode > 0) {
          // HTTP header has been send and Server response header has been handled
          
  
          // file found at server
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            String payload = https.getString();
            //Serial.println(payload);
            return payload;
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

void loop() {
  int debugSensors = 1;
if(debugSensors == 0){
 Serial.println("temperature");
  String temperature = readTemperature();
  Serial.println("---------------------");
  Serial.println(temperature);
  Serial.println("---------------------");
  ReadingTypeEnum type = TEMPERATURE;
  sendValueToApi(String(temperature), type);
  String result = requestDesiredValues(type);
  Serial.println("value read:     " + temperature);
  Serial.println("desired value:  " + result);

  delay(1000);

  Serial.println("");
  Serial.println("");

  Serial.println("tds");
  float tds = readTdsValue();
  Serial.println("---------------------");
  Serial.println(tds);
  Serial.println("---------------------");
  type = EL;
  sendValueToApi(String(tds), type);
  result = requestDesiredValues(type);
  Serial.println("value read:     " + String(tds));
  Serial.println("desired value:  " + result);
  if(atof(result.c_str()) > tds){
    digitalWrite(ELSoluction, HIGH);
  }else{
    digitalWrite(ELSoluction, LOW);
  }

    Serial.println("");
    Serial.println("");
  delay(1000);


  Serial.println("ph");
  String ph = readPh();
  Serial.println("---------------------");
  Serial.println(ph);
  Serial.println("---------------------");
  type = PH;
  sendValueToApi(ph, type);
  result = requestDesiredValues(type);
  Serial.println("value read:     " + ph);
  Serial.println("desired value:  " + result);
  if(atof(result.c_str()) < atof(ph.c_str())){
    digitalWrite(AcidSoluction, HIGH);
  }else{
    digitalWrite(AcidSoluction, LOW);
  }
}else{
    Serial.println("temperature");
  String temperature = readTemperature();
  Serial.println("---------------------");
  Serial.println(temperature);
  Serial.println("---------------------");
  
  
    Serial.println("");
  Serial.println("");

 Serial.println("tds");
  float tds = readTdsValue();
  Serial.println("---------------------");
  Serial.println(tds);
  Serial.println("---------------------");

      Serial.println("");
  Serial.println("");


 Serial.println("ph");
  String ph = readPh();
  Serial.println("---------------------");
  Serial.println(ph);
  Serial.println("---------------------");
}

 
  
  


  delay(1000);
}
