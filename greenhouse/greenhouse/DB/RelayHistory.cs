using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{
    public class RelayHistory
    {
        public Guid Id { get; set; }

        public DateTime TurnedOnTime{ get; set; }

        [AllowNull]
        public DateTime TurnedOfTime { get; set; }
    }
}
