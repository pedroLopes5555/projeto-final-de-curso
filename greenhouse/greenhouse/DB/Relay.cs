using System.ComponentModel.DataAnnotations;

namespace greenhouse.DB
{
    public class Relay
    {
        public Guid Id { get; set; }

        public string? Name { get; set; }
        public bool State {  get; set; }
        public Sensor? Sensor { get; set; }

    }
}
