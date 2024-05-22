using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.Models;
using greenhouse.DB;
using System.Diagnostics;
using Microsoft.EntityFrameworkCore;
using System.Reflection.Metadata.Ecma335;
using greenhouse.Interfaces;
using System.Threading.Tasks.Dataflow;

namespace greenhouse.Controllers
{

    public class AutomationController : Controller
    {

        IGreenhouseRepository _greenhouseRepository;


        public AutomationController(IGreenhouseRepository greenhouseRepository)
        {
            _greenhouseRepository = greenhouseRepository;
        }

        public IActionResult RequestContainers()
        {
            //var context = new GreenhouseContex();
            //return Json(context.Containers.ToList());

            return Json(_greenhouseRepository.GetContainers());
        }


//        {
//    "microcontrollerId": "123",
//    "valueType": 2,
//    "value": 12.3
//}
        public IActionResult setDesiredValue([FromBody] SetDesiredValueContent content)
        {
            _greenhouseRepository.SetContainerDesiredValue(content);
            return Ok();
        }

        public IActionResult RequestUserContainers([FromBody] String userId)
        {
            return Json(_greenhouseRepository.GetUserContainers(userId));
        }

        public IActionResult RequestContainerValues([FromBody] String containerId)
        {
            return Json(_greenhouseRepository.getContainerValues(containerId));
        }

        public IActionResult RequestContainerDesiredValues([FromBody] String containerId)
        {
            return Json(_greenhouseRepository.getContainerConfigs(containerId));
        }


        public IActionResult RequestContainerMicrocontrollers([FromBody] String containerId) {
            return Json(_greenhouseRepository.getContainerMicrocontrollers(containerId));
        }

        public IActionResult GetUser([FromBody] string userId)
        {
            return Json(_greenhouseRepository.getUser(userId));
        }

        public IActionResult RequestUserPermissions([FromBody] String userId)
        {
            return Json(_greenhouseRepository.getUserPermissions(userId));
        }

    }
}
