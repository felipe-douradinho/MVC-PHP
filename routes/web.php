<?php

// -- Devices
use Golden\Routing\Router;


// -- Devices
Router::resource('/', 'DeviceController@index', 'device@index');

// -- Devices
Router::resource('/devices', 'DeviceController', 'devices');

// -- SSH Integration
Router::resource('/ssh-integration', 'SshController', 'ssh_integration');

// -- Cryptography
Router::resource('/cryptography', 'CryptographyController', 'cryptography');

// -- Hashes
Router::resource('/hashes', 'HashController', 'hashes');

// -- Reports
Router::resource('/reports', 'ReportController', 'reports');




// -- above is the same as bellow
//Router::get('/devices', 'DeviceController@index', 'device.index');
//Router::get('/devices/create', 'DeviceController@create', 'device.create');
//Router::post('/devices', 'DeviceController@store', 'device.store');
//Router::get('/devices/{resource}', 'DeviceController@show', 'device.show');
//Router::get('/devices/{resource}/edit', 'DeviceController@edit', 'device.edit');
//Router::post('/devices/{resource}', 'DeviceController@update', 'device.update');
//Router::delete('/devices/{resource}', 'DeviceController@destroy', 'device.destroy');
//Router::get('/devices/{resource}/destroy_get', 'DeviceController@destroy_get', 'device.destroy');