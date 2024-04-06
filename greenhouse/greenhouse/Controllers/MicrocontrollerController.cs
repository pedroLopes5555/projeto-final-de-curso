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


        [HttpPost]
        public IActionResult UpdateValue([FromBody] UpdateValueJsonContent content)
        {
           _greenhouseRepository.UpdateValues(content);
            return Ok();

        }
    }

}
