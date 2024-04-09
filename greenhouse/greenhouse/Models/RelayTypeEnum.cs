namespace greenhouse.Models
{
    public enum RelayTypeEnum
    {
        BASIC_SOLUTION = 0x1,
        ACID_SOLUTION = 0x1 << 1,
        CE_SOLUTION = 0x1 << 2,
    }
}
