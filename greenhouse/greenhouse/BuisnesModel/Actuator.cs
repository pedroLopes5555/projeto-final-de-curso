using greenhouse.Interfaces;

namespace greenhouse.BuisnesModel
{


    public abstract class Actuator
    {

        protected IGreenhouseRepository _greenhouseRepository;

        protected InstructionsQueue _instructionsQueue;


        public Actuator(IGreenhouseRepository greenhouseRepository)
        {
            _greenhouseRepository = greenhouseRepository;
            _instructionsQueue = new InstructionsQueue();
        }

        protected abstract Instruction CreateInstruction(string microcontrollerID);

        public void SaveInstructions(string microcontrollerID)
        {
            _instructionsQueue.AddInstruction(CreateInstruction(microcontrollerID));
        }



       
    }
}
