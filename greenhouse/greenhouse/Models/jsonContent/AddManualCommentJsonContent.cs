namespace greenhouse.Models.jsonContent
{
    public class AddManualCommentJsonContent
{
        public DateTime Start { get; set; }
        public DateTime Finish { get; set; }
        public string ContainerId { get; set; }
        public string OperationType { get; set; }
        public string Command { get; set; }
    }
}
