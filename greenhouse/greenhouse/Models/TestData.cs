using greenhouse.DB;
using Microsoft.EntityFrameworkCore.Metadata.Conventions.Infrastructure;
using System;
namespace greenhouse.Models
{
    public class TestData : ITestData
    {

        public IEnumerable<User> getTestData()
        {
            var result = new List<User>();
            var guid = new Guid();

            result.Add(new User
            {
                Containers = new List<Container>()
                {
                    new Container
                    {
                        Id = Guid.NewGuid(),
                        Dimension = 10,
                        Name = "Test1",
                        Location = "ali em cima 1",
                        DesiredValues = getValues(),
                        Values = getValues(),
                        Microcontrollers = getMicrocontrollers(),
                    },
                    new Container
                    {
                        Id = Guid.NewGuid(),
                        Dimension = 10,
                        Name = "Test2",
                        Location = "ali em cima 2",
                        DesiredValues = getValues(),
                        Values = getValues(),
                        Microcontrollers = getMicrocontrollers(),
                    }
                },
                Email = "pedrolopes@hotmail.com",
                Id = Guid.NewGuid(),
                Super = true,
                UserName = "Test",
                UserPassword = "password",

            });

            return result;
        }



        private List<Value> getValues()
        {
            var result = new List<Value>();

            for (var i = 0; i < 5; i++)
            {
                Random random = new Random();

                var value = new Value()
                {

                    Id = Guid.NewGuid(),
                    ElectricalConductivity = 533 + i,
                    Ph = random.Next(0, 14),
                    Temperature = random.Next(0, 100),
                    Time = DateTime.Now,
                };
                result.Add(value);
            }

            return result;
        }



        //generate Microcontrollers
        private List<Microcontroller> getMicrocontrollers()
        {

            var result = new List<Microcontroller>();

            for (int i = 0; i < 5; i++)
            {
                result.Add(new Microcontroller
                {
                    Capacity = 10,
                    Id = Guid.NewGuid().ToString(),
                    Name = $"Microcontroller{i}",
                    Relays = getRelays(),
                });
            }

            return result;
        }


        private List<Relay> getRelays()
        {
            var result = new List<Relay>();

            for (int i = 0; i < 3; i++)
            {
                result.Add(new Relay
                {
                    Id = Guid.NewGuid(),
                    Name = $"relay{i}",
                    State = false,
                    Sensor = new Sensor
                    {
                        Id = new Guid(),
                        Name = "pH",
                        Type = "pH"
                    }
                });
            }

            return result;
        }

    }








}
