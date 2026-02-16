<?php

namespace App\Http\Controllers;

use App\Models\ContactGroup;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactGroupController extends Controller
{
    // Display the contact management page with pagination
    public function index(Request $request)
    {
        $groups = ContactGroup::withCount('contacts')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('contact', compact('groups'));
    }

    // Store a new contact group and process the file
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:txt,csv|max:2048'
        ]);

        // Create the group with user association
        $group = ContactGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        // Process the file immediately
        $file = $request->file('file');
        $this->processContactFile($group, $file);

        return redirect()->route('contact-groups.show', $group->id)
               ->with('success', 'Group created with contacts imported successfully!');
    }

    // Process the uploaded contact file
    private function processContactFile(ContactGroup $group, $file)
    {
        $path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'csv') {
            $this->processCSV($group, $path);
        } else {
            $this->processTXT($group, $path);
        }
    }

    // Process CSV file
    private function processCSV(ContactGroup $group, $filePath)
    {
        $file = fopen($filePath, 'r');
        
        // Skip header row
        fgetcsv($file);
        
        $batch = [];
        $batchSize = 100;
        
        while (($line = fgetcsv($file)) !== false) {
            if (count($line) >= 3) {
                $batch[] = [
                    'contact_group_id' => $group->id,
                    'full_name' => $line[0],
                    'company_name' => $line[1],
                    'company_email' => $line[2],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                if (count($batch) >= $batchSize) {
                    Contact::insert($batch);
                    $batch = [];
                }
            }
        }
        
        if (!empty($batch)) {
            Contact::insert($batch);
        }
        
        fclose($file);
    }

    // Process TXT file
    private function processTXT(ContactGroup $group, $filePath)
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $batch = [];
        $batchSize = 100;
        
        foreach ($lines as $line) {
            $parts = preg_split('/\t|,/', $line);
            
            if (count($parts) >= 3) {
                $batch[] = [
                    'contact_group_id' => $group->id,
                    'full_name' => trim($parts[0]),
                    'company_name' => trim($parts[1]),
                    'company_email' => trim($parts[2]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                if (count($batch) >= $batchSize) {
                    Contact::insert($batch);
                    $batch = [];
                }
            }
        }
        
        if (!empty($batch)) {
            Contact::insert($batch);
        }
    }

    // Show contacts for a group with pagination
    public function show(ContactGroup $contactGroup)
    {
        $contacts = $contactGroup->contacts()
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
                      
        return view('contacts.show', compact('contactGroup', 'contacts'));
    }

    // Update a contact
    public function updateContact(Request $request, Contact $contact)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255'
        ]);

        $contact->update($request->only(['full_name', 'company_name', 'company_email']));
        return back()->with('success', 'Contact updated successfully!');
    }

    // Delete a contact
    public function destroyContact(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Contact deleted successfully!');
    }

    // Add a new contact to a group
    public function addContact(Request $request, ContactGroup $contactGroup)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255'
        ]);

        $contactGroup->contacts()->create($request->all());
        return back()->with('success', 'Contact added successfully!');
    }
}