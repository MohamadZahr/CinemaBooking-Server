<?php
return [
    '/register' => ['controller' => 'UserController', 'method' => 'register'],
    '/login'    => ['controller' => 'UserController', 'method' => 'login'],

    '/movies'              => ['controller' => 'MovieController', 'method' => 'getAllMovies'],
    '/released_movies'     => ['controller' => 'MovieController', 'method' => 'getReleasedMovies'],
    '/now_showing'         => ['controller' => 'MovieController', 'method' => 'getNowShowing'],
    '/upcoming_movies'     => ['controller' => 'MovieController', 'method' => 'getUpcoming'],

    '/create_showtime'   => ['controller' => 'ShowtimeController', 'method' => 'createShowtime'],
    '/delete_showtime'   => ['controller' => 'ShowtimeController', 'method' => 'deleteShowtime'],
    '/showtimes'         => ['controller' => 'ShowtimeController', 'method' => 'getShowtimes'],

    '/create_booking'      => ['controller' => 'BookingController', 'method' => 'createBooking'],
    '/available_seats'     => ['controller' => 'BookingController', 'method' => 'getAvailableSeats'],
    '/bookings'         => ['controller' => 'BookingController', 'method' => 'getBookings'],


];
