namespace greenhouse.Models
{
    public enum ReadingTypeEnum
    {
        PH = 0x1,
        EL = 0x1 << 1,
        TEMPERATURE = 0x1 << 2,
    }
}