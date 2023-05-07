const fs = require('fs');

const Models = fs.readdirSync(__dirname + '/Models');
const Requests = fs.readdirSync(__dirname + '/Requests');
const Notifications = fs.readdirSync(__dirname + '/Notifications');
const Observers = fs.readdirSync(__dirname + '/Observers');
const Controllers = fs.readdirSync(__dirname + '/Controllers');
const routes = fs.readdirSync(__dirname + '/routes');

// Create directories if they don't exist
const directories = [
    'stubs/Models', 'stubs/Requests', 'stubs/Notifications', 'stubs/Observers', 'stubs/Controllers'];
directories.forEach((directory) => {
    fs.mkdirSync(__dirname + '/' + directory, {recursive: true});
});

function fixNamespace(content, targetNamespace) {
    return content.replace(/namespace\sSanderCokart\\LaravelApiAuth\\(\w*)/gm, `namespace ${targetNamespace}`);
}

function fixUseStatements(content) {
    //Things that should be locally imported should change
    return content.replace(/use\s(SanderCokart\\LaravelApiAuth)\\(?!Traits|Contracts)(\w+)\\(\w+)?/g, 'use App\\$2\\vendor\\$1\\$3');
}

/**
 *
 * @param entity - Controller, Model, Request, Notification, Observer
 * @param srcDir - Directory relative to this file
 * @param targetNamespace - Directory relative to App
 */
function fix(entity, srcDir, targetNamespace) {
    let content = fs.readFileSync(`${__dirname}/${srcDir}/${entity}`, 'utf8');
    content = fixNamespace(content, targetNamespace);
    content = fixUseStatements(content);
    const withStub = entity.replace(/\.php/g, '.stub');

    if (!fs.existsSync(`${__dirname}/stubs/${srcDir}`)) {
        fs.mkdirSync(`${__dirname}/stubs/${srcDir}`, {recursive: true});
    }

    fs.writeFileSync(`${__dirname}/stubs/${srcDir}/${withStub}`, content);
}

//loop through models and replace namespace SanderCokart\LaravelApiAuth\Models; with namespace App\Models and save to stubs/Models with stub extension
Models.forEach((model) => {
    fix(model, 'Models', 'App\\Models\\vendor\\SanderCokart\\LaravelApiAuth');
});

Requests.forEach((request) => {
    fix(request, 'Requests', 'App\\Http\\Requests\\vendor\\SanderCokart\\LaravelApiAuth');
});

Notifications.forEach((notification) => {
    fix(notification, 'Notifications', 'App\\Notifications\\vendor\\SanderCokart\\LaravelApiAuth');
});

Observers.forEach((observer) => {
    fix(observer, 'Observers', 'App\\Observers\\vendor\\SanderCokart\\LaravelApiAuth');
});

Controllers.forEach((controller) => {
    fix(controller, 'Controllers', 'App\\Http\\Controllers\\vendor\\SanderCokart\\LaravelApiAuth');
});

routes.forEach((route) => {
    fix(route, 'routes', 'App\\Http\\Controllers\\vendor\\SanderCokart\\LaravelApiAuth');
});
