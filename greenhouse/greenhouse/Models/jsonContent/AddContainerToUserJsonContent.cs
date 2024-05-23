using greenhouse.DB;

namespace greenhouse.Models.jsonContent
{
    public class AddContainerToUserJsonContent
    {
        public String name { get; set; }
        public String location { get; set; }

        public Guid userId { get; set; }

    }

}
