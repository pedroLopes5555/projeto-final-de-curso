using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.DB;
using System.Diagnostics;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        private static string _microcontrollerID = "";
        private static string _type = "";
        private static string _value = "";

        static private string _temperatureValue = "";
        static private string _tdsValue = "";
        static private string _phValue = "";



        public void saveData(String microcontrollerId, String type,String value)
        {

            DateTime time = DateTime.Now;

            //check if microcontroller exists oi database
            using (var context = new GreenhouseContex())
            {
                var microcontroller = context.Microcontrollers.SingleOrDefault(x => x.Id == microcontrollerId);


                if (microcontroller != null)
                {
                    microcontroller.Capacity = microcontroller.Capacity + 1;
                }
                else
                {
                    var newMicrocontroller = new Microcontroller()
                    {
                        Id = microcontrollerId,
                        Name = microcontrollerId,
                        Capacity = 0,
                    };

                    /*
                    var newValue = new Value()
                    {
                        EletricalCondutivity = 0,
                        Temperature = 0,
                        Id = new Guid(),
                        Ph = 0,
                        Time = time,
                    };*/
                    context.Microcontrollers.Add(newMicrocontroller);
                    //context.Values.Add(newValue);
                }
                    context.SaveChanges();
            }
        }
        


        //[HttpPost("message")]
        public IActionResult ReciveSensorData([FromBody] Message sensorData)
         {


            //Context context = Context.GetInstance();

            // Save data into controller variables
            _microcontrollerID = sensorData.MicrcocontrollerID;
            _type = sensorData.Type; 
            _value = sensorData.Value;
            
            saveData(_microcontrollerID,_type,_value);



            if (_type == "tds" && _value != null)
            {
                _tdsValue = _value;
            }
            if(_type == "temperature" && _value != null)
            {
                
                _temperatureValue = _value;
            }
            if(_type == "ph" && _value != null)
            {
                _phValue = _value;
            }


            
            return Json("micrcocontrollerID:" + _microcontrollerID + " type:" + _type + " value:" + _value);
        }





        //test funtion
        public IActionResult requestValues()
        {
            var environmentData = new
            {
                Temperature = _temperatureValue + " C",
                Tds = _tdsValue + " ppm",
                Ph = _phValue + " ph"
            };

             return Json(environmentData);
        }





        public IActionResult testRequest() 
        {

            var enviromentData = new {
                test = ":)"
            };

            return Json(enviromentData);
        }


        public IActionResult SendTest([FromBody] Message sensorData)
        {
            _microcontrollerID = sensorData.MicrcocontrollerID;
            _type = sensorData.Type;
            _value = sensorData.Value;

            
            return Json("\nmicrocontroller id -> " + _microcontrollerID + "\ntype -> " + _type + "\nvalue -> " + _value);

        }


    }
}
