using Microsoft.EntityFrameworkCore;
using System.Diagnostics;

namespace greenhouse.DB
{
    public class GreenhouseContex : DbContext
    {

        public GreenhouseContex()
        {
            Database.EnsureDeleted();
            Database.EnsureCreated();
        }

        public DbSet<Relay> Relays { get; set; }
        public DbSet<Microcontroller> Microcontrollers { get; set; }
        public DbSet<Sensor> Sensors { get; set; }
        public DbSet<Container>  Container { get; set; }
        public DbSet<Value> Values { get; set; }
        
        public DbSet<Permission> Permissions { get; set; }

        public DbSet<PermsRelations> PermsRelations { get; set; }

        public DbSet<Users> Users { get; set; }




        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            object value = optionsBuilder.UseSqlServer("Server=(localdb)\\mssqllocaldb;Database=greenhouseDB;Trusted_Connection=True;");
        }




        /*
        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Contaier>()
                .HasOne(c => c.Value)
                .WithOne(v => v.Container)
                .HasForeignKey<Value>(v => v.Container.Id);

            // Other configurations...

            base.OnModelCreating(modelBuilder);
        }*/



    }

}
