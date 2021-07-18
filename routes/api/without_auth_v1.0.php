<?php

$router->get('create-token', function () {
  $data['status'] = 200;
  $data['success'] = 1;
  $data['message'] = "Token Created Successfully!";
  $data['data']['token'] = create_token();
  return response()->json($data, $data['status']);
});

$router->get('users', 'UserController@all_users');

$router->group(['prefix' => 'parkings'], function () use ($router) {
  $router->get('available', 'ParkingController@available_parkings');
  $router->get('occupied', 'ParkingController@occupied_parkings');
  $router->post('book', 'UserParkingController@create_booking');
  $router->patch('reached/{id}', 'UserParkingController@user_reached_parking');
  $router->patch('exited/{id}', 'UserParkingController@user_exited_parking');
});
