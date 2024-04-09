using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{
    public class Relay
    {
        public Guid Id { get; set; }
        public string? Name { get; set; }

        [AllowNull]
        public List<RelayHistory> History { get; set; }
    }
}
