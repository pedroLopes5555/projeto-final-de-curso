using greenhouse.DB;




namespace greenhouse.Models
{
    public interface ITestData
    {
        IEnumerable<User> getTestData();
    }
}
