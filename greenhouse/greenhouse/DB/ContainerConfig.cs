using greenhouse.Models;
using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Numerics;

namespace greenhouse.DB
{
    [PrimaryKey(nameof(ContainerId), nameof(ReadingType))]
    public class ContainerConfig
    {
        public Guid ContainerId { get; set; }

        public ReadingTypeEnum ReadingType { get; set; }

        public float Value { get; set; }

        public float Margin { get; set; }

        public double ActionTime { get; set; }
    }
}
