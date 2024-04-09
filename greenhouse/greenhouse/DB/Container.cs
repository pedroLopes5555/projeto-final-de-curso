using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace greenhouse.DB
{
    public class Container
    {
        [Key]
        public Guid Id { get; set; }
        public List<ScannedValue> Values { get; set; }
        public string? Name { get; set; }

        public List<ContainerConfig> Configs { get; set; }

        public List<Relay> Relays { get; set; }

        public List<Sensor> Sensors { get; set; }
        public List<Microcontroller> Microcontrollers { get; set; }

        [Required]
        public int Dimension { get; set; }
        
        [Required]
        public string Location { get; set; }


        public Container()
        {
            
        }



    }
}
