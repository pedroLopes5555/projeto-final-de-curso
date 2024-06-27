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
                if (lastPhValue.Reading < config.Value + config.Margin)
                {
                    command = "OPEN:ph+";
                }
                //if the value is on the margin make no command

                //create result
                Instruction result = new Instruction()
                {
                    ExecutionTime = DateTime.Now,
                    DeviceId = microcontrollerID,
                    Command = command
                };

                _instructionsQueue.AddInstruction(result);

                if (result.Command.Length > 3) result.Command = "CLOSE:" + command.Substring(command.Length - 3);

                result.ExecutionTime = result.ExecutionTime.AddSeconds(config.ActionTime);
                _instructionsQueue.AddInstruction(result);
            }

        }
    }
}
