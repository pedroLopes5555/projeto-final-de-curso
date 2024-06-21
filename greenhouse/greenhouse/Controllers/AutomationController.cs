using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using greenhouse.DB;
using System.Diagnostics;
using Microsoft.EntityFrameworkCore;
using System.Reflection.Metadata.Ecma335;
using greenhouse.Interfaces;
using System.Threading.Tasks.Dataflow;
using greenhouse.Models.jsonContent;
using Microsoft.JSInterop.Implementation;
using System.Text.RegularExpressions;

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

        public IActionResult SetContainerConfig([FromBody] SetDesiredValueContent content)
        {
            _greenhouseRepository.SetContainerConfig(content);
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


        public IActionResult GetUserMicrocontrollers()
        {
            //TODO
            return Ok();
        }

        public IActionResult RegistNewUser([FromBody] User user)
        {
            _greenhouseRepository.registUser(user);
            return Json(user);
        }

        public IActionResult UserLogin([FromBody] LoginJsonContent content)
        {
            return Json(_greenhouseRepository.UserLogin(content));
        }

        public IActionResult addContainerToUser([FromBody] AddContainerToUserJsonContent content)
        {

            _greenhouseRepository.createNewContainer(content);
            return Ok();

        }


    }
}
