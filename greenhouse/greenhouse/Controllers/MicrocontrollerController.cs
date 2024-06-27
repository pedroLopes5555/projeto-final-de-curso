using greenhouse.BuisnesModel;
using greenhouse.DB;
using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Models.jsonContent;
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
        private readonly PhActuator _phActuator;
        private readonly ElActuator _elActuator;



        public MicrocontrollerController(IGreenhouseRepository greenhouseRepository,InstructionsQueue queue ,PhActuator phActuator, ElActuator elActuator)
        {
            _greenhouseRepository = greenhouseRepository;
            _queue = queue;
            _phActuator = phActuator;
            _elActuator = elActuator;

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
           

            _phActuator.EvalAndAct(content.MicrocontrollerId);
            _elActuator.EvalAndAct(content.MicrocontrollerId);


            return Ok();

        }

        [HttpPost]
        public IActionResult GetNextAction([FromBody] string microcontrollerId)
        {
            var result = _queue.GetNextInstrution(microcontrollerId);
            return Json(result.Command);
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
            var result = new AddManualCommentJsonContent()
            {
                Command = "OPEN:el+",
                ContainerId = "ED516CC2-7115-4EB2-9C13-36F7ED444302",
                Finish = DateTime.Now.AddHours(1),
                Start = DateTime.Now,
                OperationType = "el"

            };
            return Json(result);
        }

    }

}
