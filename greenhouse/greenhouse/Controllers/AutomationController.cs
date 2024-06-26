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

        public IActionResult CreateNewContainer([FromBody] AddContainerToUserJsonContent content)
        {
            var result = _greenhouseRepository.createNewContainer(content);


            var obj = new
            {
                containerCreated = (result != Guid.Empty),
                id = result
            };


            return Json(obj);
        }

        public IActionResult EditContainer([FromBody] Container container)
        {
            return Json(_greenhouseRepository.EditContainer(container));
        }




        public IActionResult GetUserMicrocontrollers([FromBody] string userId)
        {
            //TODO
            return Ok();
        }

        public IActionResult RegistNewUser([FromBody] User user)
        {
            var result = _greenhouseRepository.registUser(user);


            var obj = new
            {
                userCreated = (result != Guid.Empty),
                id = result
            };


            return Json(obj);
        }

        public IActionResult UserLogin([FromBody] LoginJsonContent content)
        {

            var result = _greenhouseRepository.UserLogin(content);

            var obj = new
            {
                login = (result != Guid.Empty),
                id = result
            };

            return Json(obj);
        }


        //TODO------------------------------------------------------------
        public IActionResult DeleteUser([FromBody] string userId)
        {
            return Ok();
        }

        public IActionResult Updateuser([FromBody] User user)
        {
            return Ok();
        }

        //------------------------------------------------------------



        public IActionResult addContainerToUser([FromBody] AddContainerToUserJsonContent content)
        {

            _greenhouseRepository.createNewContainer(content);
            return Ok();

        }


    }
}
