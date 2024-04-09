using greenhouse.DB;
using greenhouse.Interfaces;
using greenhouse.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;
using System.Diagnostics.CodeAnalysis;
using System.Diagnostics.Eventing.Reader;

namespace greenhouse.Controllers
{
    public class MicrocontrollerController : Controller
    {

        IGreenhouseRepository _greenhouseRepository;

        public MicrocontrollerController(IGreenhouseRepository greenhouseRepository)
        {
            _greenhouseRepository = greenhouseRepository;
        }


        [HttpGet]
        public IActionResult GetValue() 
        {
            var x = new UpdateValueJsonContent() { MicrocontrollerId = "abc", ValueType = ReadingTypeEnum.PH, Value = 3.6f };
            return Json(x);
        }




        //todo
        //if the value is diferente from the desired value take actions
        [HttpPost]
        public IActionResult UpdateValue([FromBody] UpdateValueJsonContent content)
        {
           _greenhouseRepository.UpdateValues(content);
            return Ok();

        }


        [HttpPost]
        public IActionResult GetDesiredValue([FromBody] RequestDesiredValueJsonContent content)
        {
            var result = _greenhouseRepository.GetContainerConfig(content);
            return Json(result.Value);
        }

        [HttpPost]
        public IActionResult TurnOnRelay([FromBody] ChangeRelayStateJsonContent content)
        {
            return Ok();
        }


        [HttpGet]
        public IActionResult TestJsonFormat()
        {
            var result = new RequestDesiredValueJsonContent();

            result.MicrocontrollerId = "abc";
            result.ValueType = ReadingTypeEnum.PH;

            var result1 = new MicrocontrollerValueJsonContent()
            {
                MicrocontrollerId = "abc",
                Value = 3.0f,
                ValueType = ReadingTypeEnum.EL
            };

            String userId = "ola";

            return Json(userId);
        }

    }

}
