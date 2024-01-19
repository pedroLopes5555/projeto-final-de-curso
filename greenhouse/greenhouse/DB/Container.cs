using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace greenhouse.DB
{
    public class Container
    {
        [Key]
        public int Id { get; set; }
        public Value Value { get; set; }
        public string? Name { get; set; }
        
        public List<Microcontroller> Microcontrollers { get; set; }

        [Required]
        public int Dimension { get; set; }
        
        [Required]
        public string Location { get; set; }

    }
}
