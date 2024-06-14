﻿using greenhouse.BuisnesModel;
using greenhouse.Interfaces;

namespace greenhouse.BusinessModel
{
    public abstract class Actuator
    {
        protected IGreenhouseRepository _greenhouseRepository;
        protected InstructionsQueue _instructionsQueue;

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
