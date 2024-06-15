﻿using greenhouse.BusinessModel;
using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Repositoy;

namespace greenhouse.BuisnesModel
{
    public class ElActuator : Actuator
    {

        public ElActuator(IGreenhouseRepository greenhouseRepository, InstructionsQueue instructionsQueue)
            : base(greenhouseRepository, instructionsQueue)
        {
        }



        //create the instruction for the microcontroller
        public override void EvalAndAct(string microcontrollerID)
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

            //if there is no meta value return no isntruction
            if (lastPhValue == null)
            {
                return;
            }

            //create result
            Instruction result = new Instruction()
            {
                ExecutionTime = DateTime.Now,
                DeviceId = microcontrollerID,
            };

            _instructionsQueue.AddInstruction(result);
        }
    }
}
