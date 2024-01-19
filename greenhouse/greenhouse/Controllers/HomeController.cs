using greenhouse.DB;
using greenhouse.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Diagnostics;

namespace greenhouse.Controllers
{
    public class HomeController : Controller
    {
        private readonly ILogger<HomeController> _logger;

        public HomeController(ILogger<HomeController> logger)
        {
            _logger = logger;
        }

        public IActionResult Index()
        {


            //            using (var context = new GreenhouseContex()
            //            {
            //                var container = new Contaier
            //                {
            //                    Id = 1,
            //                    Value = new Value
            //{
            //                        Id = Guid.NewGuid(),
            //                        EletricalCondutivity = 110.10F,
            //                        Ph = 7.4F,
            //                        Temperature = 19.0F
            //                    }
            //                };
            //            };

            /*
            using (var context = new GreenhouseContex())
            {

                var microcontroller = new Microcontroller
                {
                    Id = Guid.NewGuid().ToString(),
                    Name = "Arduino3",
                    Capacity = 3

                };

                context.Microcontrollers.Add(microcontroller);
                //context.SaveChanges();

                var relay = new Relay
                {
                    Name = "Relay1",
                    State = true,
                    Microcontroller = microcontroller
                };

                context.Relays.Add(relay);
                //context.SaveChanges();
                

                var container = new Container
                {
                    Id = 1,
                    Dimension = 1,
                    Location = "Em cima",
                    Name = "principal",
                    Value = new Value
                    {
                        Id = Guid.NewGuid(),
                        Temperature = 18.0F,
                        EletricalCondutivity = 110.10F,
                        Ph = 7.3F,
                        Time = new DateTime(2024,01,15),

                    },
                    Microcontrollers = new List<Microcontroller> { microcontroller }
                    
                };

                context.Add(container);
                context.SaveChanges();
            }*/


            
            using (var context = new GreenhouseContex())
            {
                var microcontroller = new Microcontroller
                {
                    Id = Guid.NewGuid().ToString(),
                    Name = "Arduino3",
                    Capacity = 3,


                };

                context.Microcontrollers.Add(microcontroller);
                Debug.WriteLine("microcontroller:");
                Debug.WriteLine(microcontroller.Id);
                Debug.WriteLine(microcontroller.Name);
                Debug.WriteLine(microcontroller.Capacity + "\n \n \n");
                context.SaveChanges();
            }



            using (var context = new GreenhouseContex())
            {
                var microcontrollers = context.Microcontrollers.FirstOrDefault();
                if (microcontrollers != null)
                {
                    var relay = new Relay
                    {
                        Name = "Relay1",
                        State = true,
                        Microcontroller = microcontrollers
                    };

                    context.Relays.Add(relay);
                    context.SaveChanges();
                }
            }

            using (var context = new GreenhouseContex())
            {
                var list = context.Microcontrollers.Where(x => x.Name.StartsWith("Ar"));
                Debug.WriteLine("\n\n\nGOT HERE:");
                foreach (var item in list)
                {
                    Debug.WriteLine(item.Name);
                }
                Debug.WriteLine("\n foreach end");

            }

            using (var context = new GreenhouseContex())
            {
                var microcontrollers = context.Microcontrollers.FirstOrDefault();
                var list = context.Microcontrollers.Where(x => x.Name.StartsWith("Ar"));

                List<Microcontroller> microocontrollersHelper = new List<Microcontroller>();

                foreach (var item in list)
                {
                    microocontrollersHelper.Add(item);
                }


                var container = new Container()
                {
                    Dimension = 1,
                    Location = "Em cima",
                    Id = 1,
                    Name = "principal",
                    Microcontrollers = microocontrollersHelper
                };

                context.Add(container); context.SaveChanges();
            }
                return View();

        }

        public IActionResult Privacy()
        {
            return View(); 
        }

        [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
        public IActionResult Error()
        {
            return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
        }
    }
}