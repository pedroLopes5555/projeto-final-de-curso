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
                Microcontrollers = new List<Microcontroller>()
                {
                    new Microcontroller()
                    {
                        Id = "34:85:18:7B:0E:C8",
                        Capacity = 10,
                        Name = "Arduino de teste",
                        Relays = GetRelays()
                    }
                },
                Configs = GetContainerConfigs()
            };

            var container2 = new Container
            {
                Id = Guid.NewGuid(),
                Dimension = 10,
                Name = "Test2",
                Location = "ali em cima 2",
                Values = GetValues(),
                Microcontrollers = GetMicrocontrollers(),
                Configs = GetContainerConfigs()
            };

            result.Add(new User
            {
                Containers = new List<Container> { container1, container2 },
                Email = "pedrolopes@hotmail.com",
                Id = Guid.NewGuid(),
                Super = true,
                UserName = "Test",
                UserPassword = "password"
            });

            return result;
        }

        private List<ScannedValue> GetValues()
        {
            var result = new List<ScannedValue>();

            // Generate sample scanned values
            for (var i = 0; i < 5; i++)
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

        private List<Microcontroller> GetMicrocontrollers()
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
                    Relays = GetRelays()
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
                    State = false,
                    Sensor = new Sensor
                    {
                        Id = Guid.NewGuid(),
                        Name = "pH",
                        Type = "pH"
                    }
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
                Type = ReadingTypeEnum.PH,
                Value = 7.0f // Placeholder value for demonstration
            });

            containerConfigs.Add(new ContainerConfig
            {
                ContainerId = Guid.NewGuid(),
                Type = ReadingTypeEnum.EL,
                Value = 500.0f // Placeholder value for demonstration
            });

            containerConfigs.Add(new ContainerConfig
            {
                ContainerId = Guid.NewGuid(),
                Type = ReadingTypeEnum.TEMPERATURE,
                Value = 25.0f // Placeholder value for demonstration
            });

            return containerConfigs;
        }
    }
}
