using greenhouse.BusinessModel;
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
            var lastECValue = container.Values.Where(y => y.ReadingType == ReadingTypeEnum.PH)
                .OrderByDescending(a => a.Time).FirstOrDefault();

            //if there is no meta value return no isntruction
            if (lastECValue == null)
            {
                return;
            }

            string command = "";

            //if lastValue is bigger that the metaValue + margin
            if (lastECValue.Reading > config.Value + config.Margin)
            {
                command = "OPEN:el-";
            }
            //if lastValue is lower that the metaValue + margin
            if (lastECValue.Reading < config.Value + config.Margin)
            {
                command = "OPEN:el+";
            }
            //if the value is on the margin make no command

            //create result
            Instruction result = new Instruction()
            {
                ExecutionTime = DateTime.Now,
                DeviceId = microcontrollerID,
                Command = command,
            };

            _instructionsQueue.AddInstruction(result);

            if(result.Command.Length > 3) result.Command = "CLOSE:" + command.Substring(command.Length - 3);

            result.ExecutionTime = result.ExecutionTime.AddSeconds(config.ActionTime);
            _instructionsQueue.AddInstruction(result);
        }
    }
}
