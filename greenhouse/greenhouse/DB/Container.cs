using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{
    public class Container
    {
        [Key]
        public Guid Id { get; set; }
        public List<ScannedValue> Values { get; set; }
        public string? Name { get; set; }
        [AllowNull]
        public List<ContainerConfig> Configs { get; set; }
        [AllowNull]
        public List<Relay> Relays { get; set; }
        [AllowNull]
        public List<Sensor> Sensors { get; set; }
        [AllowNull]
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
