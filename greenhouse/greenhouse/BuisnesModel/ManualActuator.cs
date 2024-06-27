using greenhouse.BusinessModel;
using greenhouse.Interfaces;
using greenhouse.Models.jsonContent;

namespace greenhouse.BuisnesModel
{
    public class ManualActuator : Actuator
    {
        public ManualActuator(IGreenhouseRepository greenhouseRepository, InstructionsQueue instructionsQueue) : base(greenhouseRepository, instructionsQueue)
        {
        }

        public override void EvalAndAct(string microcontrollerID)
        {
            throw new NotImplementedException();
        }

        public void AddManualCommand(string containerId, string operationType, DateTime start, DateTime finish, string command)
        {
            _instructionsQueue.ClearInstructionsFor(containerId, operationType);

            var microcontrollers = _greenhouseRepository.getContainerMicrocontrollers(containerId);

            foreach(var microcontroller in microcontrollers)
            {
                Instruction startInstruction = new Instruction()
                {
                    DeviceId = microcontroller.Id,
                    ExecutionTime = start,
                    Command = command,
                };
                _instructionsQueue.AddInstruction(startInstruction);
                
                Instruction finishInstruction = new Instruction()
                {
                    DeviceId = microcontroller.Id,
                    ExecutionTime = finish,
                    Command = command.Replace("OPEN", "CLOSE"),
                };
                _instructionsQueue.AddInstruction(finishInstruction);
            }

        }
    }
}
