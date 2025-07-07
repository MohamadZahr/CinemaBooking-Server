<?php
return [
    '/register' => ['controller' => 'UserController', 'method' => 'register'],
    '/login'    => ['controller' => 'UserController', 'method' => 'login'],

    '/movies'              => ['controller' => 'MovieController', 'method' => 'getAllMovies'],
    '/released_movies'     => ['controller' => 'MovieController', 'method' => 'getReleasedMovies'],
    '/now_showing'         => ['controller' => 'MovieController', 'method' => 'getNowShowing'],
    '/upcoming_movies'     => ['controller' => 'MovieController', 'method' => 'getUpcoming'],

];
