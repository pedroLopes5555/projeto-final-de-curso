using greenhouse.BuisnesModel;
using greenhouse.DB;
using greenhouse.Interfaces;
using greenhouse.Repositoy;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllersWithViews();
builder.Services.AddScoped<IGreenhouseRepository, GreenhouseRepository>();
builder.Services.AddScoped<GreenhouseContex, GreenhouseContex>();

builder.Services.AddSingleton<InstructionsQueue>();

builder.Services.AddScoped<PhActuator>();
builder.Services.AddScoped<ElActuator>();
builder.Services.AddScoped<ManualActuator>();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
    app.UseHsts();
}



app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

app.UseAuthorization();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");




app.Run();
