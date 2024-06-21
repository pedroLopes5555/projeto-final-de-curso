using greenhouse.DB;
using greenhouse.Models;
using greenhouse.Models.jsonContent;
using Microsoft.AspNetCore.Components.Web;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Routing.Constraints;

namespace greenhouse.Interfaces
{
    public interface IGreenhouseRepository
    {
        IQueryable<DB.Container> GetContainers(); //todo -> change fo comtainers by user-

        void SetContainerConfig(SetDesiredValueContent content);

        void UpdateValues(UpdateValueJsonContent content);

        ContainerConfig GetMicrocontrollerContainerConfig(RequestDesiredValueJsonContent content);

        IQueryable<DB.Container> GetUserContainers(String userId);

        IQueryable<ScannedValue> getContainerValues(String containerId);

        DB.Container getMicrocontrollerContainer(string microcontrollerId);

        IQueryable<ContainerConfig> getContainerConfigs(String containerId);

        IQueryable<Microcontroller> getContainerMicrocontrollers(String containerId);

        void changeRelayState(ChangeRelayStateJsonContent content);

        Permission getUserPermissions(String userId);

        User getUser(String userId);

        void registUser(User user);

        bool UserLogin(LoginJsonContent content);

        void createNewContainer(AddContainerToUserJsonContent content);

        //IQueryable<Microcontroller> getUserMicrocontroller(String userId);

    }
}
