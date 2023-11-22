using System.ComponentModel.DataAnnotations;

namespace greenhouse.DB
{
    public class Contaier
    {
        [Key]
        public int Id { get; set; }
        public string? Name { get; set; }
        
        [Required]
        public int Dimension { get; set; }
        
        [Required]
        public string Location { get; set; }

        public Value Value { get; set; }
    }
}
