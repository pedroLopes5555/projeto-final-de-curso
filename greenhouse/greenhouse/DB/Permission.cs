using System.ComponentModel.DataAnnotations;
using System.Diagnostics.CodeAnalysis;


namespace greenhouse.DB
{
    public enum Permission
    {
        ADMIN = 0X1,
        USER = 0x1 <<1,

    }
}
