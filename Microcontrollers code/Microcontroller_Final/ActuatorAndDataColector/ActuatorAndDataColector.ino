/**
   BasicHTTPSClient.ino

    Created on: 14.10.2018

*/

#include <Arduino.h>

#include <WiFi.h>
#include <WiFiMulti.h>

#include <HTTPClient.h>

#include <WiFiClientSecure.h>

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

  ReadingTypeEnum type = PH;
  sendValueToApi("1", type);
  Serial.println("sended ph");

  type = EL;
  sendValueToApi("900", type);
  Serial.println("sended El");

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
