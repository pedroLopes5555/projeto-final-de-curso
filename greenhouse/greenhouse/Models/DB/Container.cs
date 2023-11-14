using System.Transactions;

namespace greenhouse.Models.DB
{
    public class Container
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public int Dimension { get; set; }
        public string Location { get; set; }
        public List<Microcontroller> Microcontrollers { get; set; }
        public Value Values { get; set; }


    }
}
