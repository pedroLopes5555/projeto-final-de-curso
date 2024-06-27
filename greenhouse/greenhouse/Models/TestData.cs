using greenhouse.DB;
using System;
using System.Collections.Generic;

namespace greenhouse.Models
{
    public class TestData : ITestData
    {
        public IEnumerable<User> GetTestData()
        {
            var result = new List<User>();

            // Create sample data for demonstration
            var container1 = new Container
            {
                Id = Guid.NewGuid(),
                Dimension = 10,
                Name = "Test1",
                Location = "ali em cima 1",
                Values = GetValues(),
                Relays = GetRelays(),
                Configs = GetContainerConfigs()
            };

            var microcontrollers = new List<Microcontroller>()
            {
                new Microcontroller()
                {
                    Id = "pythonArduino",
                    Capacity = 10,
                    Name = "Arduino de teste",
                    Container = container1
                }
            };

            var container2 = new Container
            {
                Id = Guid.NewGuid(),
                Dimension = 10,
                Name = "Test2",
                Location = "ali em cima 2",
                Values = GetValues(),
                Configs = GetContainerConfigs(),
                Relays = GetRelays(),

            };

            var MicrocontrollersContainer2 = GetMicrocontrollers(container2);

            result.Add(new User
            {
                Containers = new List<Container> { container1, container2 },
                Email = "pedrolopes@hotmail.com",
                Id = Guid.NewGuid(),
                Super = true,
                UserName = "Test",
                UserPassword = "password",
                Permissions = Permission.ADMIN,
                
            });

            using (var context = new GreenhouseContex())
            {
                var users = result;
                context.Users.Add(users.FirstOrDefault(a => a.UserName == "Test"));
                context.Microcontrollers.Add(new Microcontroller()
                {
                    Id = "pythonArduino",
                    Capacity = 10,
                    Name = "Arduino de teste",
                    Container = container1
                });

                context.SaveChanges();
            }

            return result;
        }

        private List<ScannedValue> GetValues()
        {
            var result = new List<ScannedValue>();

            // Generate sample scanned values
            for (var i = 0; i < 1; i++)
            {
                var value = new ScannedValue()
                {
                    Id = Guid.NewGuid(),
                    Reading = 533 + i,
                    ReadingType = ReadingTypeEnum.EL,
                    Time = DateTime.Now
                };
                result.Add(value);
            }

            return result;
        }

        private List<Microcontroller> GetMicrocontrollers( Container container)
        {
            var result = new List<Microcontroller>();

            // Generate sample microcontrollers
            for (int i = 0; i < 5; i++)
            {
                result.Add(new Microcontroller
                {
                    Capacity = 10,
                    Id = Guid.NewGuid().ToString(),
                    Name = $"Microcontroller{i}",
                    Container = container
                });
            }

            return result;
        }

        private List<Relay> GetRelays()
        {
            var result = new List<Relay>();

            // Generate sample relays
            for (int i = 0; i < 3; i++)
            {
                result.Add(new Relay
                {
                    Id = Guid.NewGuid(),
                    Name = $"relay{i}",
                });
            }

            return result;
        }


        public List<ContainerConfig> GetContainerConfigs()
        {
            var containerConfigs = new List<ContainerConfig>();

            // Create three container configurations with different types
            containerConfigs.Add(new ContainerConfig
            {
                ContainerId = Guid.NewGuid(),
                ReadingType = ReadingTypeEnum.PH,
                Value = 7.0f, // Placeholder value for demonstration
                ActionTime = 300,
                Margin = 0.5f
            });

            containerConfigs.Add(new ContainerConfig
            {
                ContainerId = Guid.NewGuid(),
                ReadingType = ReadingTypeEnum.EL,
                Value = 500.0f, // Placeholder value for demonstration
                ActionTime = 300,
                Margin = 200
            });

            containerConfigs.Add(new ContainerConfig
            {
                ContainerId = Guid.NewGuid(),
                ReadingType = ReadingTypeEnum.TEMPERATURE,
                Value = 25.0f, // Placeholder value for demonstration
                ActionTime = 300,
                Margin = 200,
            });

            return containerConfigs;
        }
    }
}
