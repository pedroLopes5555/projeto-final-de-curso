using System.Windows.Markup;

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
            LoadData();
            return _context;
        }

        public static void LoadData()
        {


            List<Microcontroller> list = new List<Microcontroller>();
            Microcontroller microcontroller1 = new Microcontroller();
            microcontroller1.Id = "2C:54:91:88:C9:E3";
            microcontroller1.Name = "a definir";
            microcontroller1.Capacity = 5;
            
            List<Sensor> sensors = new List<Sensor>();
            Sensor sensor0 = new Sensor();
            sensor0.Id = 0;
            sensor0.Type = "pH";

            Sensor sensor1 = new Sensor();
            sensor1.Id = 1;
            sensor1.Type = "ec";

            Sensor sensor2 = new Sensor();
            sensor2.Id = 2;
            sensor2.Type = "distance";

            Sensor sensor3 = new Sensor();
            sensor3.Id = 3;
            sensor3.Type = "temperature";

            microcontroller1.Sensors = sensors;



            List<Relay> relays = new List<Relay>(1);

            Relay relay0 = new Relay();
            relay0.Id = 0;
            relay0.Name = "bomba_peristaltica_1";
            relay0.State = true;

            microcontroller1.Relays = relays;

            list.Add(microcontroller1);



            Value values = new Value();
            values.Ph  = 9.37F;
            values.EletricalCondutivity = 150.02F;
            values.Temp = 15.4F;
            values.Date = System.DateTime.Now;






            var ctx = GetInstance();

            ctx.Containers.Add(new Container()
            {
                Id = 2,
                Name = "test",
                Dimension = 10,
                Location = "zona norte, tanque 2",
                Microcontrollers = list,
                Values = values

            });
        }
    }
}