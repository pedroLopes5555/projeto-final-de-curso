using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;


namespace greenhouse.DB
{
    public class Permission
    {

        [Key]
        public Guid Id { get; set; }
        public string Name { get; set; }
    }
}
