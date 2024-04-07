﻿using Microsoft.EntityFrameworkCore;
using System.Diagnostics;

namespace greenhouse.DB
{
    public class GreenhouseContex : DbContext
    {

        public GreenhouseContex()
        {
            //uncommet Database.EnsureDeleted(); and run to update data base
            //Database.EnsureDeleted();
            Database.EnsureCreated();
        }

        public DbSet<Relay> Relays { get; set; }
        public DbSet<Microcontroller> Microcontrollers { get; set; }
        public DbSet<Sensor> Sensors { get; set; }
        public DbSet<Container>  Containers { get; set; }
        public DbSet<ScannedValue> Values { get; set; }

        public DbSet<ContainerConfig> Configs { get; set; }



        //public DbSet<Permission> Permissions { get; set; }

        //public DbSet<PermsRelations> PermsRelations { get; set; }

        public DbSet<User> Users { get; set; }




        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {

            optionsBuilder.EnableSensitiveDataLogging();
            object value = optionsBuilder.UseSqlServer("Server=tcp:greenhousetfc.database.windows.net,1433;Initial Catalog=GreenHouse;Persist Security Info=False;User ID=ghapp;Password='sf68Kjç#2hha&gA';MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;");
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
