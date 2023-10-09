#include <DFRobot_DHT11.h>

#define DHT11_PIN 2  //DHt sensor digital pin
const int tdsSensorPin = A0; // Define the analog pin for the TDS sensor

DFRobot_DHT11 DHT;  //inicialize the sensor varuiable

void getTdsValue(){

  int sensorValue = analogRead(tdsSensorPin); // Read the analog voltage from the TDS sensor
  float tdsValue = map(sensorValue, 0, 1023, 0, 5000); // Map the analog value to TDS values (adjust the range as needed)

  Serial.print("Raw Sensor Value: ");
  Serial.println(sensorValue);
  Serial.print("TDS Value (ppm): ");
  Serial.println(tdsValue);

  //todo
  //send https POST to the web server

}

void getTemperatureStatus(){
  DHT.read(DHT11_PIN);
  Serial.print("temp:");
  Serial.print(DHT.temperature);
  Serial.print("  humi:");
  Serial.println(DHT.humidity);

  //todo
  //send https POST to the web server
}

//todo

//read PH



void setup() {
  Serial.begin(115200);
}



void loop() {
  
  getTdsValue();
  
  //getTemperatureStatus();

  /*
  it will be from minute to minute, but now it waits 1 second just for testing 
  */
  delay(1000); // Delay for a second (adjust as needed)

}
