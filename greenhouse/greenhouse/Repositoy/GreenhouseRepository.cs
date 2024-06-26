using greenhouse.Controllers;
using greenhouse.DB;
using greenhouse.Interfaces;
using greenhouse.Models;
using Microsoft.VisualBasic;
using System.Data;
using System.Text.RegularExpressions;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration.EnvironmentVariables;
using System.ComponentModel;
using greenhouse.Models.jsonContent;

namespace greenhouse.Repositoy
{
    public class GreenhouseRepository : IGreenhouseRepository
    {

        public GreenhouseContex _context;

        public GreenhouseRepository(GreenhouseContex contex) 
        {
        _context = contex;
        }


        public IQueryable<DB.Container> GetContainers()
        {
            return _context.Containers;

        }





        //Update desired values of a container 
        public void SetContainerConfig(SetDesiredValueContent content)
        {
            var containerId = Guid.Parse(content.ContainerId);

            if(!_context.Containers.Any(x => x.Id == containerId))
                throw new ArgumentOutOfRangeException(nameof(containerId),$"Container {containerId} does not exist");


            var config = _context.Configs.Where(a => a.ContainerId == containerId && a.ReadingType == content.ValueType).FirstOrDefault();

            if (config == null)
            {
                _context.Configs.Add(new ContainerConfig()
                {
                    ReadingType = content.ValueType,
                    ContainerId = containerId,
                    Value = content.DesiredValue,
                    Margin = content.Margin,
                    ActionTime = content.ActionTime,
                });
            }
            else
            {
                config.Value = content.DesiredValue;
                config.Margin = content.Margin;
                config.ActionTime = content.ActionTime; 
            }

            _context.SaveChanges();

        }

        public DB.Container  getMicrocontrollerContainer(string microcontrollerId)
        {
            //find microcontroller
            var microcontroller = _context.Microcontrollers.Include(x => x.Container).FirstOrDefault(a => a.Id == microcontrollerId);

            if(microcontroller == null) { throw new Exception("microcontroller not found"); }

            return microcontroller.Container;
        }
            



        // microcontroller calls this endpoint sending tha values collected
        public void UpdateValues(UpdateValueJsonContent content)
        {
            //first we need the container taht the microcontroller belongs

            var microcontroller = _context.Microcontrollers.
                Include(a => a.Container).ThenInclude(a => a.Values).
                SingleOrDefault(a => a.Id == content.MicrocontrollerId);

            //check if id exists
            if (microcontroller == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller {content.MicrocontrollerId} do not exist");

            }

            var container = microcontroller.Container;

            //check if container exists
            if (container == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller do not have container");

            }

            container.Values.Add(new ScannedValue()
            {
                Id = new Guid(),
                Reading = content.Value,
                ReadingType = content.ValueType,
                Time = DateTime.Now,
            });


            _context.SaveChanges();
        }




        //get container desired value
        public ContainerConfig GetMicrocontrollerContainerConfig(RequestDesiredValueJsonContent content)
        {
            //first we need the container taht the microcontroller belongs

            var microcontroller = _context.Microcontrollers.
                Include(a => a.Container).ThenInclude(a => a.Configs).
                SingleOrDefault(a => a.Id == content.MicrocontrollerId);

            //check if id exists
            if (microcontroller == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller {content.MicrocontrollerId} do not exist");

            }

            var container = microcontroller.Container;

            //check if container exists
            if (container == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller do not have container");

            }

            return container.Configs.SingleOrDefault(a => a.ReadingType == content.ValueType);
        }







        public IQueryable<DB.Container> GetUserContainers(String userId)
        {

            var serIDGuid = Guid.Parse(userId);
            //get user
            var user = _context.Users.
                Include(a => a.Containers).
                SingleOrDefault(a => a.Id.Equals(serIDGuid));

            //null check
            if (user == null)
            {
                throw new ArgumentOutOfRangeException($"User {userId} not found");
            }

            //get containers
            var containers = user.Containers;

            //null check
            if (containers == null)
            {
                throw new ArgumentOutOfRangeException($"User´s containers not found");
            }


            return containers.AsQueryable();
        }




        public IQueryable<ScannedValue>? getContainerValues(String containerId)
        {
            var guidContainerId = Guid.Parse(containerId);
            var container = _context.Containers.Include(a => a.Values).Where(a => a.Id == guidContainerId).SingleOrDefault();

            //check if container is null
            if (container == null)
            {
                throw new IndexOutOfRangeException($"Container {containerId} not found");
            }

            var values = container.Values;

            return values.AsQueryable();

        }


        public IQueryable<ContainerConfig> getContainerConfigs(String containerId)
        {
            var containerIDGuid = Guid.Parse(containerId);

            var configs = _context.Configs.Where(a => a.ContainerId == containerIDGuid);

            if (configs == null)
            {
                throw new IndexOutOfRangeException($"Container {containerId} not found");
            }

            return configs.AsQueryable();

        }
        

        public bool EditContainer(DB.Container container)
        {
            var dbContainer = _context.Containers.FirstOrDefault(a => a.Id == container.Id);

            if(dbContainer == null)
            {
                return false;
            }

            dbContainer.Name = container.Name;
            dbContainer.Location = container.Location;

            _context.SaveChanges();
            return true;
        }




        public IQueryable<Microcontroller> getContainerMicrocontrollers(String containerId)
        {
            var microcontrollers = _context.Microcontrollers.Where(a => a.Container.Id == Guid.Parse(containerId));

            if (microcontrollers == null)
            {
                throw new IndexOutOfRangeException($"Container or microcontrollers not found");
            }
            
            return microcontrollers.AsQueryable();
        }






        public void changeRelayState(ChangeRelayStateJsonContent content)
        {
            var microcontroller = _context.Microcontrollers.
              Include(a => a.Container).ThenInclude(a => a.Relays).ThenInclude(a => a.History).
              SingleOrDefault(a => a.Id == content.MicrocontrollerId);

            //check if id exists
            if (microcontroller == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller {content.MicrocontrollerId} do not exist");

            }

            var container = microcontroller.Container;

            if (container == null)
            {
                throw new IndexOutOfRangeException("Container Not Found");
            }

            var relay = container.Relays.SingleOrDefault(a => a.Type == content.RelayType);

            if (relay == null)
            {
                throw new IndexOutOfRangeException("Relay Not Found");
            }

        }






        public Permission getUserPermissions(String userId)
        {
            Guid guid = Guid.Parse(userId);

            var user = _context.Users.FirstOrDefault(a => a.Id == guid);

            if(user == null)
            {
                throw new Exception("User not found");
            }

            return user.Permissions;

        }


        //public Container GetMicrocontrollerContainer(string microcontrollerId)
        //{
        //    _context.Containers.FirstOrDefault(a => a.)

        //}


        public User getUser(String userId)
        {
            Guid guid = Guid.Parse(userId);

            var user = _context.Users.FirstOrDefault(a => a.Id == guid);

            if (user == null)
            {
                throw new Exception("User not found");
            }

            return user;
        }



        public Guid registUser(User user)
        {
            var asher = new PasswordHasher();

            var id = Guid.NewGuid();


            // check if user_name exists
            if ((_context.Users.FirstOrDefault(a => a.UserName == user.UserName)) != null) {
                return Guid.Empty;             
            }

            User userResult = new User()
            {
                Containers = null,
                Email = "",
                Id = id,
                Permissions = user.Permissions,
                Super = user.Super,
                UserName = user.UserName,
                UserPassword = asher.HashPassword(user.UserPassword)
            };

            _context.Users.Add(userResult);
            _context.SaveChanges();

            return id; 
        }

        public Guid UserLogin(LoginJsonContent content)
        {
            var asher = new PasswordHasher();

            User user;

            if(content.UserName == null)
            {
                user = _context.Users.FirstOrDefault(a => a.Email == content.Email);

            }
            else
            {
                user = _context.Users.FirstOrDefault(a => a.UserName == content.UserName);
            }

            if(user == null) { return Guid.Empty; }

            
            if (asher.VerifyPassword(user.UserPassword, content.Password))
            {
                return user.Id;
            }

            return Guid.Empty;
        }



        public Guid createNewContainer(AddContainerToUserJsonContent content)
        {
            //get user
            var user = _context.Users.Include(y => y.Containers).FirstOrDefault(a => a.Id == content.userId);

            //check if null
            if(user == null)
            {
                return Guid.Empty;
            }

            //Add container to user
            var container = new DB.Container
            {
                Id = Guid.NewGuid(),
                Location = content.location,
                Name = content.name,
                Dimension = 10,
                Configs = null,
                Microcontrollers = null,
                Relays = null,
                Sensors = null,
                Values = null
            };

            if(user.Containers == null)
            {
                user.Containers = new List<DB.Container> { container };
            }
            else
            {
                user.Containers.Add(container);
                _context.Containers.Add(container);
            }

            _context.SaveChanges();

            return container.Id;
        }



        //public IQueryable<Microcontroller> getUserMicrocontroller(String userId)
        //{
        //    //get user
        //    var user = _context.Users.Include(y => y.M).FirstOrDefault(a => a.Id == Guid.Parse(userId));

        //    //check if null
        //    if (user == null)
        //    {
        //        throw new Exception("Users id not found");
        //    }

        //    //ger Microcontrollers

        //    //var microcontrollers = _context.Microcontrollers.Include(a => a.Users).Where(u => u.Users)



        //}

    }
}
