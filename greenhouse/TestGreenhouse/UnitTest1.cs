using greenhouse.Controllers;
using greenhouse.Models;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System.Security.Cryptography.X509Certificates;
using static Microsoft.EntityFrameworkCore.DbLoggerCategory.Database;

namespace TestGreenhouse
{
    [TestClass]
    public class UnitTest1
    {
        [TestMethod]
        public void TestInstrutionQueue()
        {
            var queue = new InstructionsQueue();

            var expected = new Instruction()
            {
                Command = "ph+",
                DeviceId = "teste1",
                ExecutionTime = DateTime.Now.AddDays(-4)
            };

            queue.AddInstruction(expected);

            queue.AddInstruction(new Instruction()
            {
                Command = "ph+",
                DeviceId = "teste1",
                ExecutionTime = DateTime.Now.AddDays(-2)
            });
            queue.AddInstruction(new Instruction()
            {
                Command = "ph+",
                DeviceId = "teste1",
                ExecutionTime = DateTime.Now.AddDays(-3)
            });
            queue.AddInstruction(new Instruction()
            {
                Command = "ph+",
                DeviceId = "teste1",
                ExecutionTime = DateTime.Now.AddDays(-1)
            });

            var result = queue.GetNextInstrution("teste1");

            Assert.AreEqual(expected.Command, result.Command);
            Assert.AreEqual(expected.DeviceId, result.DeviceId );
            Assert.AreEqual(expected.ExecutionTime, result.ExecutionTime);
            
        }
    }
}