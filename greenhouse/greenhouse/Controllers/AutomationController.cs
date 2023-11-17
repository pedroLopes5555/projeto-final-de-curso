using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.Models.DB;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        private string? microcontrollerID;
        private string? type;
        private string? value;


        private static String _temperatureValue = "null";
        private static String _tdsValue = "null";



        public IActionResult Index()
        {


            Context a = new Context();
            return View();

        }


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

            return Json("micrcocontrollerID:" + microcontrollerID + " type:" + type + " value:" + value);
        }





        //test funtion
        public IActionResult requestValues()
        {
            var environmentData = new 
            {
                Temperature = _temperatureValue + " C",
                Tds = _tdsValue + " ppm"
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
