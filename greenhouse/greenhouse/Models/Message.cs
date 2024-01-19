namespace greenhouse.Models
{
    public class Message
    {

        //
        //public IActionResult SendSensorData(/*[FromBody]*/ string micrcocontrollerID, string type, string value)


        public string? MicrcocontrollerID { get; set; }
        public string? Type { get; set; }
        public string? Value { get; set; }


    }
}
