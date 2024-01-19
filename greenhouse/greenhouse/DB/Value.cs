using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;

namespace greenhouse.DB
{
    public class Value
    {
        [Key]
        public Guid Id { get; set; }
        public float Ph {  get; set; }
        public float EletricalCondutivity { get; set; }
        public float Temperature { get; set; }
        public DateTime Time { get; set; }

        //public Contaier Container { get; set; }
        //public Contaier Container { get; set; }




    }
}
