namespace greenhouse.Models
{
    public class SetDesiredValueContent
    {
        public String ContainerId { get; set; }
        public float DesiredValue { get; set; }
        public ReadingTypeEnum ValueType { get; set; }

    }
}
