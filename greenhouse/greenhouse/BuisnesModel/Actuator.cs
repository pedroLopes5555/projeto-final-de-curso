using greenhouse.Interfaces;
using greenhouse.Models;

namespace greenhouse.BuisnesModel
{


    public abstract class Actuator
    {

        IGreenhouseRepository _greenhouseRepository;

        InstructionsQueue _instructionsQueue;


        public Actuator(IGreenhouseRepository greenhouseRepository, InstructionsQueue instructionsQueue)
        {
            _greenhouseRepository = greenhouseRepository;
            _instructionsQueue = instructionsQueue;
        }

        protected abstract Instruction CreateInstruction(string microcontrollerID);

        public void SaveInstructions(string microcontrollerID)
        {
            _instructionsQueue.AddInstruction(CreateInstruction(microcontrollerID));
        }



       
    }
}
