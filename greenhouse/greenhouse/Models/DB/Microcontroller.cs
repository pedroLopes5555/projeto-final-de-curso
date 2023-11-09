namespace greenhouse.Models.DB
{
    public class Microcontroller
    {
        private int Id { get; set; }
        private string Name { get; set; }
        private int Capacity { get; set; }

        private List<Sensor> Sensors { get; set; }

        private List<Relay> Relays { get; set; }

    }
}
