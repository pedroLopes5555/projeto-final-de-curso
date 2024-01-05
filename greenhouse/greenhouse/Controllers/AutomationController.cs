using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.DB;
using System.Diagnostics;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        private static string microcontrollerID = "";
        private static string type = "";
        private static string value = "";

        static private string _temperatureValue = "";
        static private string _tdsValue = "";
        static private string _phValue = "";


        


        //[HttpPost("message")]
        public IActionResult ReciveSensorData([FromBody] Message sensorData)
         {


            //Context context = Context.GetInstance();

            // Save data into controller variables
            microcontrollerID = sensorData.MicrcocontrollerID;
            type = sensorData.Type; 
            value = sensorData.Value;

            /*
             data base
             
             */

            if(type == "tds" && value != null)
            {
                _tdsValue = value;
            }
            if(type == "temperature" && value != null)
            {
                _temperatureValue = value;
            }
            if(type == "ph" && value != null)
            {
                _phValue = value;
            }

            return Json("micrcocontrollerID:" + microcontrollerID + " type:" + type + " value:" + value);
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
            microcontrollerID = sensorData.MicrcocontrollerID;
            type = sensorData.Type;
            value = sensorData.Value;

            
            return Json("\nmicrocontroller id -> " + microcontrollerID + "\ntype -> " + type + "\nvalue -> " + value);

        }


    }
}
