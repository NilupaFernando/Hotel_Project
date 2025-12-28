<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function contactUs()
    {
        return inertia('Contact');
    }

    public function save(ContactRequest $request)
    {
        try {
            $validated = $request->validated();
            Contact::create($validated);
            Mail::to('nilupafernando0505@gmail.com')->send(new TestMail($validated));
            return "Email sent and contact saved!";
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

}
