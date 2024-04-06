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


            var config = _context.Configs.Where(a => a.ContainerId == containerId && a.Type == content.Type).FirstOrDefault();

            if (config == null)
            {
                _context.Configs.Add(new ContainerConfig()
                {
                    Type = content.Type,
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
            if(microcontroller == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller {content.MicrocontrollerId} do not exist");

            }

            var container = microcontroller.Container;

            //check if container exists
            if (container == null)
            {
                throw new ArgumentOutOfRangeException($"Microcontroller do not have container");

            }


        }



    }
}
