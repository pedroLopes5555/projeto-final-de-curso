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
        public void SetContainerDesiredValue(SetDesiredValueContent content)
        {
            var containerId = Guid.Parse(content.ContainerId);

            if(!_context.Containers.Any(x => x.Id == containerId))
                throw new ArgumentOutOfRangeException(nameof(containerId),$"Container {containerId} does not exist");


            var config = _context.Configs.Where(a => a.ContainerId == containerId && a.Type == content.ValueType).FirstOrDefault();

            if (config == null)
            {
                _context.Configs.Add(new ContainerConfig()
                {
                    Type = content.ValueType,
                    ContainerId = containerId,
                    Value = content.DesiredValue,
                });
            }
            else
            {
                config.Value = content.DesiredValue;
            }

            _context.SaveChanges();

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
        public ContainerConfig GetContainerConfig(RequestDesiredValueJsonContent content)
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

            return container.Configs.SingleOrDefault(a => a.Type == content.ValueType);
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
        

        public IQueryable<Microcontroller> getContainerMicrocontrollers(String containerId)
        {
            var microcontrollers = _context.Microcontrollers.Where(a => a.Container.Id == Guid.Parse(containerId));

            if (microcontrollers == null)
            {
                throw new IndexOutOfRangeException($"Container or microcontrollers not found");
            }
            
            return microcontrollers.AsQueryable();
        }


    }
}
