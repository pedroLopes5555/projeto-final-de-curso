using greenhouse.DB;
using greenhouse.Models;
using Microsoft.AspNetCore.Mvc;
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