using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;
using System.Numerics;
using System.Transactions;

namespace greenhouse.DB
{
    public class User
    {
        [Key]
        public Guid Id { get; set; }

        public string UserName { get; set; }


        [Required]
        public String UserPassword { get; set; }

        [Required]
        public string Email { get; set; }

        [AllowNull]

        public Permission Permissions { get; set; }
        [AllowNull]
        public List<Container> Containers { get; set; }    
        public bool Super {  get; set; }


    }

}
