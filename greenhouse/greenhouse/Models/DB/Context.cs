namespace greenhouse.Models.DB
{
    public class Context
    {


        private static Context _context = new Context();
        public Context()
        {
            Containers = new List<Container>()
;       }

        public List<Container> Containers { get; set; }

        //outros

        public static Context GetInstance()
        {
            return _context;
        }

        public static void LoadData()
        {
            var ctx = GetInstance();

            ctx.Containers.Add(new Container()
            {
                Id = 2,
                Name = "test"
            });
        }
    }
}
