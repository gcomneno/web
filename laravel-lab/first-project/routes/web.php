<?php
/*  Qui si introduce la facade. In Laravel una facade sembra una classe usata staticamente, tipo: Route::get(...)
    ma dietro le quinte Laravel sta delegando quella chiamata a un oggetto reale gestito dal container.
    Quindi non è semplicemente “chiamo un metodo statico”; è una scorciatoia comoda per accedere a servizi già configurati.
*/
use Illuminate\Support\Facades\Route;

// Il secondo argomento è una closure, cioè una "funzione anonima" eseguita quando quella rotta viene visitata.
/* In quesot esempio la root home-page viene indirizzata verso il rendering della view denominata "welcome"
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return response('home', 200);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
