using Microsoft.AspNetCore.Mvc;
using greenhouse.Models.ValueClass;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.Models.Microcontrollers;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        private string microcontrollerID;
        private string type;
        private string value;
        public List<Value> values = new List<Value>();
        public IActionResult Index()
        {
            return View();
        }





        //public IActionResult SendSensorData(/*[FromBody]*/ string micrcocontrollerID, string type, string value)
        //{
        //    //sensor ID:[values]

        //    //values.Add(new Value(type, float.Parse(value)));

        //    //Invoke-RestMethod  -uri https://localhost:7220/automation/sendsensordata -Method Post -Body @{micrcocontrollerID = "1"; sensorId = "12"; type = "pH" ; value = "3,0"}

        //    if(type == "distance" && float.TryParse(value, out float result))
        //    {
        //        values.Add(new Value(type, result));
        //    }

        //    foreach (Value element in values) 
        //    {
        //        Console.WriteLine(element.ToString());
        //    }

        //    return Json("micrcocontrollerID:" + micrcocontrollerID + " type:" + type + " value:" + value);
        //}




        //[HttpPost("message")]
        public IActionResult ReciveSensorData([FromBody] Message sensorData)
        {
            // Save data into controller variables
            microcontrollerID = sensorData.MicrcocontrollerID;
            type = sensorData.Type;
            value = sensorData.Value;



            return Json("micrcocontrollerID:" + microcontrollerID + " type:" + type + " value:" + value);
        }
    }
}
