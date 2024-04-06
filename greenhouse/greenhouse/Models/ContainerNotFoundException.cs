namespace greenhouse.Models
{
    public class ContainerNotFoundException : Exception
    {
        public ContainerNotFoundException() { }
        public ContainerNotFoundException(String message) 
            : base($"container {message} not found") 
        {
            
        }
        
    }
}
