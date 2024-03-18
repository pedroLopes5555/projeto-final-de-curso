using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.DB;
using System.Diagnostics;
using Microsoft.EntityFrameworkCore;
using System.Reflection.Metadata.Ecma335;

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

        ITestData _testData;

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
        


        
        //responce whit the desired value
        public IActionResult ReciveSensorData([FromBody] Message sensorData)
        {


            // Save data into controller variables
            _microcontrollerID = sensorData.MicrcocontrollerID;
            _type = sensorData.Type; 
            _value = sensorData.Value;
            
            saveData(_microcontrollerID,_type,_value);




            
            return Json(_microcontrollerID + " type:" + _type + " value:" + _value);
        }



        public IActionResult requestData()
        {
            _testData = new TestData();
            List<User> test = _testData.getTestData().ToList();

            //var contex = new GreenhouseContex();

            //return Json("ok");
            return Json(test);
        }


        public IActionResult setDesiredValues(Value values, string microcontrollerID)
        {

            using (var context = new GreenhouseContex())
            {
                var microcontroller = context.Microcontrollers.Where(x => x.Id == microcontrollerID).FirstOrDefault();

                if (microcontroller == null) return Json("Microcontroller Not Find");

                //create new desired values instance

                values.Time = DateTime.Now;

                microcontroller.Container.DesiredValues.Add(values);

            }
            return Json("OK");
        }



        //public IActionResult TestRequestContainers(List<Guid> guids)
        //{

        //    _testData = new TestData();


        //    return Json(data.getContainersData());
        //}





        //get container
        //update container
        //delete container

        //SendUsers

    }
}
