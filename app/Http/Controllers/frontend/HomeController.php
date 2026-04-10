<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Contact;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use App\Mail\ContactAdminMail;
use App\Mail\ContactThankYouMail;
use App\Models\AboutSection;
use App\Models\Feature;
use App\Models\PageTitle;
use App\Models\Skill;
use App\Models\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    // === Home
    public function index(Request $request)
    {
        $hero = Cache::remember('hero_section', 3600, function () {
            return HeroSection::latest()->first();
        });

        return view('frontend.home', [
            'hero' => $hero,
        ]);
    }

    // ==== About
    public function about(Request $request)
    {
        $pageTitle = Cache::remember('page_title_about', 3600, function () {
            return PageTitle::where('page_name', 'About Us')->first();
        });

        $about = Cache::remember('about_section', 3600, function () {
            return AboutSection::first();
        });

        $stats = Cache::remember('stats', 3600, function () {
            return Stat::active()->get();
        });

        $skills = Cache::remember('skills', 3600, function () {
            return Skill::active()->get();
        });

        $features = Cache::remember('features', 3600, function () {
            return Feature::active()->get();
        });

        return view('frontend.about', [
            'pageTitle' => $pageTitle,
            'about'     => $about,
            'stats'     => $stats,
            'skills'    => $skills,
            'features'  => $features,
        ]);
    }

    // ==== Resume
    public function resume(Request $request)
    {
        return view('frontend.resume');
    }

    // ==== Services
    public function services(Request $request)
    {
        return view('frontend.services');
    }

    // ==== Service Details
    public function serviceDetails(Request $request, $slug)
    {
        return view('frontend.service-details', compact('slug'));
    }

    // ==== Portfolio
    public function portfolio(Request $request)
    {
        return view('frontend.portfolio');
    }

    // ==== Portfolio Details
    public function portfolioDetails(Request $request, $slug)
    {
        return view('frontend.portfolio-details', compact('slug'));
    }

    // ==== Contact
    public function contact(Request $request)
    {
        return view('frontend.contact');
    }

    // ==== Store Contact
    public function storeContact(ContactRequest $request)
    {
        // ===== Verify Google reCAPTCHA =====
        // $recaptchaResponse = $request->input('g-recaptcha-response');
        // $secret = env('RECAPTCHA_SECRET_KEY');
        // $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
        // $captchaSuccess = json_decode($verify);

        // if (!$captchaSuccess->success) {
        //     return back()->withInput()->with('error', 'Captcha verification failed. Please try again.');
        // }

        try {
            DB::beginTransaction();

            // ===== Save contact =====
            $contact = new Contact();
            $contact->name       = $request->name;
            $contact->email      = $request->email;
            $contact->phone      = $request->phone;
            $contact->subject    = $request->subject;
            $contact->message    = $request->message;
            $contact->created_at = Carbon::now();
            $contact->save();

            DB::commit();

            // ===== Prepare mail data =====
            $mailData = [
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'subject'     => $request->subject,
                'message'     => $request->message,
                'received_at' => now()->toDateTimeString(),
            ];

            // ===== Send Emails Immediately =====
            Mail::to('codingthunder1997@gmail.com')->send(new ContactAdminMail($mailData));
            Mail::to($request->email)->send(new ContactThankYouMail($mailData));

            return redirect()->route('frontend.thank-you');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contact Submission Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong! Please try again later.');
        }
    }

    // === Under Construction
    public function underConstruction(Request $request)
    {
        return view('frontend.under-construction');
    }

    // === Thank You
    public function thankYou(Request $request)
    {
        return view('frontend.thank-you');
    }
}
