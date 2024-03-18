using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{
    public class Microcontroller
    {
        [Key]
        public string? Id { get; set; }

        [DisallowNull]
        public string? Name { get; set; }
        public int Capacity { get; set; }
        public List<Relay> Relays { get; set; } = new List<Relay>();
        //public Container Container { get; set; }

    }
}
