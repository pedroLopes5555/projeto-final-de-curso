using Microsoft.AspNetCore.Mvc;
using greenhouse.Models.ValueClass;
using Microsoft.AspNetCore.Routing.Constraints;

namespace greenhouse.Controllers
{
    public class AutomationController : Controller
    {
        public List<Value> values = new List<Value>();
        public IActionResult Index()
        {
            return View();
        }


        public IActionResult SendSensorData(string micrcocontrollerID, string type, string value)
        {
            //sensor ID:[values]

            //values.Add(new Value(type, float.Parse(value)));

            //Invoke-RestMethod  -uri https://localhost:7220/automation/sendsensordata -Method Post -Body @{micrcocontrollerID = "1"; sensorId = "12"; type = "pH" ; value = "3,0"}

            if(type == "distance" && float.TryParse(value, out float result))
            {
                values.Add(new Value(type, result));
            }

            foreach (Value element in values) 
            {
                Console.WriteLine(element.ToString());
            }

            return Json("micrcocontrollerID:" + micrcocontrollerID + " type:" + type + " value:" + value);
        }



    }
}
