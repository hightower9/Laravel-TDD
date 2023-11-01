<?php

use Google\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Refer URL for details
 * 
 * https://github.com/googleapis/google-api-php-client/blob/main/examples/simple-file-upload.php
 */
Route::get('drive', function () {
    $client = new Client();
    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect_uri'));
    $client->setScopes(config('services.google.scopes'));

    $client->setAccessType('offline');

    $url = $client->createAuthUrl();

    return redirect($url);
});

Route::get('google-drive/callback', function () {
    $code = request('code');
    $client = new Client();
    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect_uri'));
    $access_token = $client->fetchAccessTokenWithAuthCode($code);

    return $access_token;
});

Route::get('upload', function () {
    $access_token = "ya29.a0AfB_byCzxSvvPENtzDD7BZ42PjprfUQgbyaVOE4LcqiweZJSy7T-NdWlkN6ootV_1p0GQqXyJIXWmQUNbCzUdQDP6_j4TJIh0quPUJ4dpphjdK5kYwznPGSO0FXZM4ZQ3y0zYSqVvOeP71hMi4SQ64YEWut9DwromYEJaCgYKAYASARESFQGOcNnCw-eXky9fbRO37t9hVQzMVA0171";
    $client = new Client();
    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect_uri'));
    $client->setAccessToken($access_token);

    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();

    // We'll setup an empty 1MB file to upload.
    DEFINE("TESTFILE", 'testfile-small.txt');
    if (!file_exists(TESTFILE)) {
        $fh = fopen(TESTFILE, 'w');
        fseek($fh, 1024 * 1024);
        fwrite($fh, "!", 1);
        fclose($fh);
    }

    $file->setName("Hello World!");
    $result2 = $service->files->create(
        $file,
        [
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]
    );
});
