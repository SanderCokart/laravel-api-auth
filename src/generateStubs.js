const fs = require('fs');

const Models = fs.readdirSync(__dirname + '/Models');
const Requests = fs.readdirSync(__dirname + '/Requests');
const Notifications = fs.readdirSync(__dirname + '/Notifications');
const Observers = fs.readdirSync(__dirname + '/Observers');
const Controllers = fs.readdirSync(__dirname + '/Controllers/Auth');

// Create directories if they don't exist
const directories = ['stubs/Models', 'stubs/Requests', 'stubs/Notifications', 'stubs/Observers', 'stubs/Controllers/Auth'];
directories.forEach((directory) => {
    fs.mkdirSync(__dirname + '/' + directory, {recursive: true});
});

function commonNamespace(model, directory) {
    let content = fs.readFileSync(`${__dirname}/${directory}/${model}`, 'utf8');
    //replace 'namespace SanderCokart\LaravelApiAuth' with App
    content = content.replace(/namespace SanderCokart\\LaravelApiAuth/g, 'namespace App');
    const modelWithStubExtension = model.replace(/\.php/g, '.stub');
    fs.writeFileSync(`${__dirname}/stubs/${directory}/${modelWithStubExtension}`, content);
}

function controllerNamespace(controller) {
    let content = fs.readFileSync(`${__dirname}/Controllers/Auth/${controller}`, 'utf8');
    content = content.replace(/namespace SanderCokart\\LaravelApiAuth\\Controllers\\Auth/g, 'namespace App\\Http\\Controllers\\Auth');
    const controllerWithStubExtension = controller.replace(/\.php/g, '.stub');
    fs.writeFileSync(`${__dirname}/stubs/Controllers/Auth/${controllerWithStubExtension}`, content);
}

//loop through models and replace namespace SanderCokart\LaravelApiAuth\Models; with namespace App\Models and save to stubs/Models with stub extension
Models.forEach((model) => {
    commonNamespace(model, 'Models');
});

Requests.forEach((request) => {
    commonNamespace(request, 'Requests');
});

Notifications.forEach((notification) => {
    commonNamespace(notification, 'Notifications');
});

Observers.forEach((observer) => {
    commonNamespace(observer, 'Observers');
});

Controllers.forEach((controller) => {
    controllerNamespace(controller);
});
