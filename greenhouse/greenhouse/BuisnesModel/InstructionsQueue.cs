using Azure.Core;

namespace greenhouse.BuisnesModel
{
    public class InstructionsQueue
    {

        private List<Instruction> _instructions = new List<Instruction>();



        public void AddInstruction(Instruction instruction)
        {
            _instructions.Add(instruction);
        }

        public Instruction GetNextInstrution(string deviceId)
        {

            var instruction = _instructions.Where(a => a.DeviceId.Equals(deviceId))
                .OrderBy(a => a.ExecutionTime).FirstOrDefault();

            _instructions.Remove(instruction);


            return instruction;
        }

    }

    public class Instruction
    {
        public string DeviceId { get; set; }
        public string Command { get; set; }

        public DateTime ExecutionTime { get; set; }
    }


}
