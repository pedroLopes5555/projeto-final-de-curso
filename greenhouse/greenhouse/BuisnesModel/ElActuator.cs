﻿using greenhouse.BusinessModel;
using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Models.jsonContent;
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
            if(_instructionsQueue.HasPendingInstructionsFor(microcontrollerID, ":el"))
            {
                return;
            } 


            //get the config, to get the value to comapre
            var config = _greenhouseRepository.GetMicrocontrollerContainerConfig(new RequestDesiredValueJsonContent()
            {
                MicrocontrollerId = microcontrollerID,
                ValueType = ReadingTypeEnum.EL
            });


            //get the last write value on the database

            var container = _greenhouseRepository.getMicrocontrollerContainer(microcontrollerID);
            var lastECValue = container.Values.Where(y => y.ReadingType == ReadingTypeEnum.EL)
                .OrderByDescending(a => a.Time).FirstOrDefault();



            //if there is no meta value return no isntruction
            if (lastECValue == null)
            {
                return;
            }

            string command = "";

            if (config == null) return;

            //if lastValue is bigger that the metaValue + margin
            if (lastECValue.Reading > config.Value + config.Margin)
            {
                command = "OPEN:el-";
            }
            //if lastValue is lower that the metaValue + margin
            if (lastECValue.Reading < config.Value - config.Margin)
            {
                command = "OPEN:el+";
            }
            //if the value is on the margin make no command



            if(command == "")
            {
                return;
            }





            //create OpenInstruction
            Guid pairGuid = new Guid();

            Instruction OpenInstruction = new Instruction()
            {
                ExecutionTime = DateTime.Now,
                DeviceId = microcontrollerID,
                Command = command,
                PairGuid = pairGuid
            };

            _instructionsQueue.AddInstruction(OpenInstruction);


            Instruction closeInstruction = new Instruction()
            {
                Command = (command.Length > 3) ? command = "CLOSE:" + command.Substring(command.Length - 3) : "",
                DeviceId = microcontrollerID,
                ExecutionTime = OpenInstruction.ExecutionTime.AddSeconds(config.ActionTime),
                PairGuid = pairGuid
            };
            _instructionsQueue.AddInstruction(closeInstruction);
        }
    }
}
