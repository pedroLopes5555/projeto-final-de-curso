    namespace greenhouse.Models
{
    public class UpdateValueJsonContent
    {
        public string MicrocontrollerId { get; set; }
        public ReadingTypeEnum ValueType { get; set; }
        public float Value { get; set; }

    }
}
