using greenhouse.Models;
using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;

namespace greenhouse.DB
{
    public class ScannedValue
    {
        [Key]
        public Guid Id { get; set; }
        public float Reading { get; set; }

        public ReadingTypeEnum ReadingType { get; set; }

        [AllowNull]
        public DateTime? Time { get; set; }


        //public Contaier Container { get; set; }
        //public Contaier Container { get; set; 
    }
}
