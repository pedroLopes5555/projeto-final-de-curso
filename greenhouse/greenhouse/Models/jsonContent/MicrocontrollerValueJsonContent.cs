namespace greenhouse.Models.jsonContent
{
    public class MicrocontrollerValueJsonContent
    {
        public string MicrocontrollerId { get; set; }
        public ReadingTypeEnum ValueType { get; set; }
        public float Value { get; set; }

    }
}
