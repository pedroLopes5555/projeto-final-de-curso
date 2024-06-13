using greenhouse.Interfaces;
using greenhouse.Models;
using greenhouse.Repositoy;

namespace greenhouse.BuisnesModel
{
    public class PhActuator : Actuator
    {

        public PhActuator(IGreenhouseRepository greenhouseRepository, InstructionsQueue instructionsQueue)
            : base(greenhouseRepository, instructionsQueue)
        {
        }

        public override Instruction CreateInstruction(string microcontrollerID)
        {
            return new Instruction
            {

            };
        }
    }
}
