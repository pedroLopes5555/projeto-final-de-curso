namespace greenhouse.Models.DB
{
    public class Microcontroller
    {
        public string Id { get; set; }
        public string Name { get; set; }
        public int Capacity { get; set; }
        
        public List<Sensor> Sensors { get; set; }
       
        public List<Relay> Relays { get; set; }

    }
}
