using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;
using System.Numerics;

namespace greenhouse.DB
{
    public class User
    {
        [Key]
        public Guid Id { get; set; }

        public string UserName { get; set; }


        [Required]
        public String UserPassword { get; set; }

        public bool Super {  get; set; }

        [Required]
        public string Email { get; set; }

        //[AllowNull]
        //public Permission Permission { get; set; }

        [Required]
        public List<Container> Containers { get; set; }    

    }
}
