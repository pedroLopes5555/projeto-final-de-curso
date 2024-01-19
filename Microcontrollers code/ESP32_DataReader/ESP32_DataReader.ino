#include <DFRobot_DHT11.h>
#include <WiFi.h>
#include <WiFiClientSecure.h>

#include <OneWire.h>
#include <DallasTemperature.h>



const int tdsSensorPin = A0; // Define the analog pin for the TDS sensor

// Data wire is plugged into port 2 on the Arduino
#define ONE_WIRE_BUS 5
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

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


float readTemperature(){
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
    return tempC;
  } 
  else
  {
    Serial.println("Error: Could not read temperature data");
    return -1;
  }
}





void getTdsValue(){
  sensor::waterTemperature = 20.0;

  float rawEc = analogRead(pin::tds_sensor) * device::aref/ 4095.0;

  float temperatureCoefecient = 1.0 + 0.02 * (sensor::waterTemperature - 25.0);
  sensor::ec = (rawEc/temperatureCoefecient) * sensor::ecCalibration;
  sensor::tds = (133.42 * pow(sensor::ec, 3) - 255.86 * sensor::ec * sensor::ec + 857.39 * sensor::ec) * 0.5;
  Serial.print(F("TDS:")); Serial.println(sensor::tds);
  Serial.print(F("EC:")); Serial.println(sensor::ec, 2);
  Serial.print(F("uS:")); Serial.println(sensor::ec * 640);
  Serial.print(F("Temperature:")); Serial.println(sensor::waterTemperature,2);
}



void setup() {
  Serial.begin(115200);
}



void loop() {
  
  getTdsValue();

  //Serial.println(readTemperature());

  delay(1000); // Delay for a second (adjust as needed)

}
