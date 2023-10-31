<?php

namespace app\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\GoogleRequest;
use App\Models\Service\ExternalService;
use App\Models\Task;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GoogleServiceController extends Controller
{
    /**
     * Connect to the external resource.
     */
    public function connect(Client $client)
    {
        $client->setScopes(config('services.google.scopes'));
        $client->setAccessType('offline');

        $url = $client->createAuthUrl();

        return response()->json(['url' => $url]);
    }

    /**
     * Get the callback response from the external resource.
     */
    public function callback(GoogleRequest $request, Client $client)
    {
        $token = $client->fetchAccessTokenWithAuthCode($request->validated()['code']);

        $service = ExternalService::create([
            'user_id' => auth()->id(), 
            'name'    => 'Google', 
            'token'   => $token,
        ]);

        return $service;
    }

    /**
     * Display the specified resource.
     */
    public function upload(ExternalService $token, Client $client)
    {
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();

        // Create a json file
        $file_name = 'tasks.json';
        Storage::put($file_name, $tasks->toJson());

        // Create a zip file
        $zip = new ZipArchive();
        $zip_file_name = now()->timestamp . '-tasks.zip';

        if($zip->open(Storage::path($zip_file_name), ZipArchive::CREATE) == true)
        {
            $zip->addFile(Storage::path($file_name), $file_name);
            $zip->close();
            Storage::delete($file_name);
        }
        
        $client->setAccessToken($token->token['access_token']);

        $service = new Drive($client);
        $file = new DriveFile();

        $file->setName("Hello World!.zip");
        $service->files->create(
            $file,
            [
                'data' => file_get_contents(Storage::path($zip_file_name)),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
        
        Storage::delete($zip_file_name);

        return response()->json(['message' => 'Uploaded'], 201);
    }
}
