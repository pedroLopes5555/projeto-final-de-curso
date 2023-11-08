using System.Transactions;

namespace greenhouse.Models.DB
{
    public class Container
    {
        private int containerId { get; set; }
        private string containerName { get; set; }
        private int containerDimension { get; set; }
        private string containerLocation { get; set; }
        private List<Microcontroller> microcontrollers { get; set; }


    }
}
