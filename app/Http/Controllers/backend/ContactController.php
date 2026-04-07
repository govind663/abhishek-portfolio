<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Contact\StoreContactInfoRequest;
use App\Http\Requests\Backend\Contact\UpdateContactInfoRequest;
use App\Models\ContactInfo;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $contacts = ContactInfo::active()->latestId()->get();

        return view('backend.contact.index', [
            'contacts' => $contacts
        ]);
    }

    public function create()
    {
        return view('backend.contact.create');
    }

    public function store(StoreContactInfoRequest $request)
    {
        try {
            $this->service->store($request->validated());

            return redirect()->route('contact.index')
                ->with('message', 'Contact created successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    public function edit($id)
    {
        $contact = ContactInfo::findOrFail($id);

        return view('backend.contact.edit', [
            'contact' => $contact
        ]);
    }

    public function update(UpdateContactInfoRequest $request, ContactInfo $contact)
    {
        try {
            $this->service->update($contact, $request->validated());

            return redirect()->route('contacts.index') // plural
                ->with('message', 'Updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $contact = ContactInfo::findOrFail($id);

            $this->service->delete($contact);

            return redirect()->route('contact.index')
                ->with('message', 'Deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}