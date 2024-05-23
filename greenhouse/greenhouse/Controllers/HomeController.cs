using greenhouse.DB;
using greenhouse.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Diagnostics;

namespace greenhouse.Controllers
{
    public class HomeController : Controller
    {

        ITestData _testData;


        private readonly ILogger<HomeController> _logger;

        public HomeController(ILogger<HomeController> logger)
        {
            _logger = logger;
        }

        public IActionResult Index()
        {
            _testData = new TestData();

            using (var context = new GreenhouseContex())
            {
                var users = _testData.GetTestData();
                context.Users.Add(users.FirstOrDefault(a => a.UserName == "Test"));
                context.SaveChanges();
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