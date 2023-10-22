using System.Security.Cryptography.Xml;

namespace greenhouse.Models.ValueClass
{
    public class Value
    {
        private float Amount { get; set; }
        private string Type {  get; set; }

        public Value(string type, float amount)
        {
            Type = type;
            Amount = amount;
        }

    }
}
