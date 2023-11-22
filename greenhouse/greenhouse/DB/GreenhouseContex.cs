using Microsoft.EntityFrameworkCore;

namespace greenhouse.DB
{
    public class GreenhouseConex
    {

        public DbSet<Microcontroller> Microcontroller { get; set; }
        public DbSet<Relay> Relays { get; set; }
        public DbSet<Sensor> Sensors { get; set; }
        public DbSet<Contaier>  Contaiers { get; set; }
        public DbSet<Value> Values { get; set; }



        //protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        //{
        //    object value = optionsBuilder.UseSqlServer("Server=(localdb)\\mssqllocaldb;Database=greenhouse;Trusted_Connection=True;");
        //}


    }

}
