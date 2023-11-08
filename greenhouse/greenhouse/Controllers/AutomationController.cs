using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        private string? microcontrollerID;
        private string? type;
        private string? value;
        public IActionResult Index()
        {
            return View();
        }


        //[HttpPost("message")]
        public IActionResult ReciveSensorData([FromBody] Message sensorData)
        {
            // Save data into controller variables
            microcontrollerID = sensorData.MicrcocontrollerID;
            type = sensorData.Type;
            value = sensorData.Value;

            /*
             data base
             
             */

            return Json("micrcocontrollerID:" + microcontrollerID + " type:" + type + " value:" + value);
        }
    }
}
