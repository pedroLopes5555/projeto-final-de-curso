using greenhouse.BusinessModel;
using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Models.jsonContent;
using greenhouse.Repositoy;

namespace greenhouse.BuisnesModel
{
    public class PhActuator : Actuator
    {

        public PhActuator(IGreenhouseRepository greenhouseRepository, InstructionsQueue instructionsQueue)
            : base(greenhouseRepository, instructionsQueue)
        {
        }



        //create the instruction for the microcontroller
        public override void EvalAndAct(string microcontrollerID)
        {
            

            if(_instructionsQueue.HasPendingInstructionsFor(microcontrollerID, ":ph"))
            {
                return;
            }

            //get the config, to get the value to comapre
            var config = _greenhouseRepository.GetMicrocontrollerContainerConfig(new RequestDesiredValueJsonContent()
            {
                MicrocontrollerId = microcontrollerID,
                ValueType = ReadingTypeEnum.PH
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

            string command = "";


            if(config != null)
            {

                //if lastValue is bigger that the metaValue + margin
                if (lastPhValue.Reading > config.Value + config.Margin)
                {
                    command = "OPEN:ph-";
                }
                //if lastValue is lower that the metaValue + margin
                if (lastPhValue.Reading < config.Value - config.Margin)
                {
                    command = "OPEN:ph+";
                }
                //if the value is on the margin make no command

                //create OpenInstruction
                Guid pairGuid = new Guid();
                Instruction OpenInstruction = new Instruction()
                {
                    ExecutionTime = DateTime.Now,
                    DeviceId = microcontrollerID,
                    Command = command,
                    PairGuid = pairGuid,
                };

                if (command == "")
                {
                    return;
                }

                _instructionsQueue.AddInstruction(OpenInstruction);

                Instruction closeInstruction = new Instruction()
                {
                    Command = (command.Length > 3) ? command = "CLOSE:" + command.Substring(command.Length - 3) : "",
                    DeviceId= microcontrollerID,
                    ExecutionTime = OpenInstruction.ExecutionTime.AddSeconds(config.ActionTime),
                    PairGuid = pairGuid,
                };


              
                _instructionsQueue.AddInstruction(closeInstruction);
            }

        }
    }
}
