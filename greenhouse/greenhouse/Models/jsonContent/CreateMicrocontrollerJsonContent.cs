using greenhouse.DB;
using Microsoft.EntityFrameworkCore.Metadata.Conventions;

namespace greenhouse.Models.jsonContent
{
    public class CreateMicrocontrollerJsonContent
    {
        public User User { get; set; }
        public Microcontroller Microcontroller { get; set; }
    }
}
