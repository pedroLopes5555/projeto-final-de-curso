using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;


namespace greenhouse.DB
{
    public class PermsRelations
    {
        [Key]
        public Guid Guid { get; set; }

        public Permission Permission { get; set; }

        public String Name { get; set; }
    }
}
