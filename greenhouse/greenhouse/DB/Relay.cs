using greenhouse.Models;
using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{

    //[PrimaryKey(nameof(Id), nameof(RelayTypeEnum))]
    public class Relay
    {
        public Guid Id { get; set; }
        public string? Name { get; set; }

        public RelayTypeEnum Type { get; set; }

        [AllowNull]
        public List<RelayHistory> History { get; set; }
    }
}
