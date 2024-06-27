using Azure.Core;
using static Microsoft.EntityFrameworkCore.DbLoggerCategory.Database;

namespace greenhouse.BuisnesModel
{
    public class InstructionsQueue
    {

        private List<Instruction> _instructions = new List<Instruction>();
        static object _lock=new object();


        public void AddInstruction(Instruction instruction)
        {
            lock (_lock)
            {
                _instructions.Add(instruction);
            }
        }
        public Instruction GetNextInstrution(string deviceId)
        {
            lock (_lock)
            {
                var instruction = _instructions.Where(a => a.DeviceId.Equals(deviceId))
                    .OrderBy(a => a.ExecutionTime).FirstOrDefault();

                //if the next instruction is still after th
                if(instruction == null)
                {
                    return new Instruction()
                    {
                        Command = "",
                        DeviceId = deviceId,
                        ExecutionTime = DateTime.Now,
                    };
                }

                if(DateTime.Now >= instruction.ExecutionTime)
                {
                    _instructions.Remove(instruction);

                }
                return instruction;
            }
        }

    }

    public class Instruction
    {
        public string DeviceId { get; set; }
        public string Command { get; set; }

        public DateTime ExecutionTime { get; set; }
    }


}
