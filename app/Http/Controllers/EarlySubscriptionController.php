<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EarlySubscription;
use Illuminate\Support\Facades\Validator;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;

class EarlySubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $currentTimestamp = time() * 1000;
        $formTimestamp = $request->input('form_timestamp');
        $submissionTime = $currentTimestamp - $formTimestamp;

        // Set a minimum time (e.g., 1 seconds) for human interaction
        $minimumTime = 1000; // 1 seconds in milliseconds

        // Check if the form was submitted too quickly
        if ($submissionTime < $minimumTime) {
            return redirect()->back()->with('error', 'Form submitted too quickly. Please try again.');
        }

        if ($request->input('form') !== null && $request->input('form') !== '') {
            return redirect()->back()->with('error', 'Spam detected. Please try again.');
        }

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:early_subscriptions,email',
                'regex:/^.+@.+\..{2,}$/'
            ]
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $email = $request->input('email');
            
            // Save the email to the database
            EarlySubscription::create(['email' => $email]);

            return redirect()->back()->with('success', 'Thank you for subscribing!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue with your subscription. Please try again.');
        }
    }
}

