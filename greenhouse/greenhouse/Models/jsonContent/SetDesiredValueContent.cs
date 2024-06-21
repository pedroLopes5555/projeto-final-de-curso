namespace greenhouse.Models.jsonContent
{
    public class SetDesiredValueContent
    {
        public string ContainerId { get; set; }
        public float DesiredValue { get; set; }
        public ReadingTypeEnum ValueType { get; set; }

        public float Margin { get; set; }

        public double ActionTime { get; set; }

    }
}
