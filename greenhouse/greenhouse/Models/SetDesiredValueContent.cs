namespace greenhouse.Models
{
    public class SetDesiredValueContent
    {
        public String ContainerId { get; set; }
        public float DesiredValue { get; set; }
        public ReadingTypeEnum ValueType { get; set; }

        public float Margin { get; set; }

        public double ActionTime { get; set; }

    }
}
