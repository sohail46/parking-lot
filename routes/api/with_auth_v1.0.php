<?php

$router->get('secure-route', function () {
  $data['status'] = 200;
  $data['success'] = 1;
  $data['message'] = "Authentication Check Successfull!";
  return response()->json($data, $data['status']);
});
