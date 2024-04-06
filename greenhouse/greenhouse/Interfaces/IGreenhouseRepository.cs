﻿using greenhouse.DB;
using greenhouse.Models;
using Microsoft.AspNetCore.Components.Web;
using Microsoft.AspNetCore.Routing.Constraints;

namespace greenhouse.Interfaces
{
    public interface IGreenhouseRepository
    {
        IQueryable<DB.Container> GetContainers(); //todo -> change fo comtainers by user-

        void SetContainerDesiredValue(SetDesiredValueContent content);

        void UpdateValues(UpdateValueJsonContent content);

        ContainerConfig GetContainerConfig(RequestDesiredValueJsonContent content);
    }
}