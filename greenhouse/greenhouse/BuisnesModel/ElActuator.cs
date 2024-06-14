﻿using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Repositoy;

namespace greenhouse.BuisnesModel
{
    public class ElActuator : Actuator
    {

        public ElActuator(IGreenhouseRepository greenhouseRepository)
            : base(greenhouseRepository)
        {
        }



        //create the instruction for the microcontroller
        protected override Instruction CreateInstruction(string microcontrollerID)
        {
            //get the config, to get the value to comapre
            var config = _greenhouseRepository.GetMicrocontrollerContainerConfig(new RequestDesiredValueJsonContent()
            {
                MicrocontrollerId = microcontrollerID,
                ValueType = ReadingTypeEnum.EL
            });


            //get the last write value on the database

            var container = _greenhouseRepository.getMicrocontrollerContainer(microcontrollerID);
            var lastPhValue = container.Values.Where(y => y.ReadingType == ReadingTypeEnum.PH)
                .OrderByDescending(a => a.Time).FirstOrDefault();

            //create result
            Instruction result = new Instruction()
            {
                ExecutionTime = DateTime.Now,
                DeviceId = microcontrollerID,
            };

            //if there is no meta value return no isntruction
            if (lastPhValue == null)
            {
                return result;
            }

            //insert instruction baed on the meta value
            result.Command = (lastPhValue.Reading > config.Value + 50) ? "el-" : "el+";

            return result;
        }
    }
}