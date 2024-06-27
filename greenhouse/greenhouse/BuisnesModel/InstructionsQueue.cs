using Azure.Core;
using greenhouse.Models;
using System.Linq.Expressions;
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


                var result = new Instruction()
                {
                    Command = "",
                    DeviceId = deviceId,
                    ExecutionTime = DateTime.Now,
                };

                //if the next instruction is still after th
                if (instruction == null || instruction.ExecutionTime > DateTime.Now)
                {
                    return result;
                }

                result.ExecutionTime = instruction.ExecutionTime;
                result.Command = instruction.Command;

                _instructions.Remove(instruction);

                return result;
            }
        }


        public bool HasPendingInstructionsFor(string deviceId, string operationType)
        {
            lock ( _lock)
            {
                return _instructions.Any(a => a.DeviceId == deviceId && a.Command.Contains(operationType));
            }
        }

        public void ClearInstructionsFor(string deviceId, string operationType)
        {
            lock (_lock)
            {
                _instructions.RemoveAll(a => a.DeviceId == deviceId && a.Command.Contains(operationType));
            }
        }

    }

    public class Instruction
    {
        public Guid PairGuid { get; set; }

        public string DeviceId { get; set; }
        public string Command { get; set; }
        public DateTime ExecutionTime { get; set; }

    }


}
