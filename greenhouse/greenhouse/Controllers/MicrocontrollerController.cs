using greenhouse.BuisnesModel;
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
        InstructionsQueue _queue;

        public MicrocontrollerController(IGreenhouseRepository greenhouseRepository)
        {
            _greenhouseRepository = greenhouseRepository;
            _queue = new InstructionsQueue();
        }


        [HttpGet]
        public IActionResult GetValue() 
        {
            var x = new UpdateValueJsonContent() { MicrocontrollerId = "abc", ValueType = ReadingTypeEnum.PH, Value = 3.6f };
            return Json(x);
        }





        //micrcontroller calls this endpoint to update the values that have collected
        //executes all the logic to sabe the commands to micrcocontroller execute
        [HttpPost]
        public IActionResult UpdateValue([FromBody] UpdateValueJsonContent content)
        {
           _greenhouseRepository.UpdateValues(content);

            

            var phActuator = new PhActuator(_greenhouseRepository);
            var elActuator = new ElActuator(_greenhouseRepository);

            phActuator.SaveInstructions(content.MicrocontrollerId);
            elActuator.SaveInstructions(content.MicrocontrollerId);


            return Ok();

        }

        [HttpPost]
        public IActionResult GetNextAction(string microcontrollerId)
        {
            var result = _queue.GetNextInstrution(microcontrollerId);
            return Json(result);
        }


        [HttpPost]
        public IActionResult GetDesiredValue([FromBody] RequestDesiredValueJsonContent content)
        {
            var result = _greenhouseRepository.GetMicrocontrollerContainerConfig(content);
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
