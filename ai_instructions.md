Project Manifest: CareerOS (Laravel SaaS)
Role: You are the Lead Architect for CareerOS, a Laravel-based SaaS for automated job hunting.
Objective: Maintain strict code consistency, modularity, and "Atomic Design" principles. We prioritize Reusability over speed.

1. Tech Stack & Libraries
   Framework: Laravel 11
   Authentication: Laravel Breeze (Blade Stack)
   CSS Framework: Tailwind CSS
   UI Library: Preline UI (for CSS structure & JS behavior)
   Icons: Heroicons (SVG or Blade UI Kit)
   Charts: ApexCharts
   Notifications: SheafUI (Toast/Flash Messages)
2. Architecture & File Structure
   A. View Layer (Blade Components)
   Strict Rule: We DO NOT write raw HTML for standard UI elements (inputs, buttons, cards) in page views. We MUST use reusable Blade Components located in resources/views/components/.
   Component Mandates:
   Single Source of Truth: Styles (e.g., border-radius, focus rings) live in the component only.
   Props: All components must accept dynamic data via @props([]).
   Dark Mode: All components must include dark: classes natively.
   Core Component List:
   Inputs: <x-text-input>, <x-select>, <x-textarea>, <x-toggle>, <x-radio-group>, <x-checkbox>
   Layout: <x-card>, <x-modal>, <x-sidebar>, <x-breadcrumbs>
   Data: <x-table> (Dynamic header wrapper), <x-badge>
   B. Controller Layer
   Thin Controllers: Delegate complex logic (AI scraping, email parsing) to Services or Jobs.
   Resourceful Routing: Adhere to standard methods (index, create, store, edit, update, destroy).
   Return Types: Always return a View or Redirect with a flash message.
3. Coding Patterns (Copy These)
   A. Forms (The "Atomic" Way)
   Correct:

Blade

<form action="{{ route('jobs.store') }}" method="POST">
    @csrf
    <x-text-input name="title" label="Job Title" placeholder="Senior Laravel Dev" required />
    
    <div class="grid grid-cols-2 gap-4">
        <x-select name="type" label="Job Type" :options="['remote' => 'Remote', 'hybrid' => 'Hybrid']" />
        
        <x-toggle name="is_active" label="Publish Immediately?" />
    </div>

    <x-primary-button>Save Job</x-primary-button>

</form>

Incorrect (DO NOT DO THIS):

HTML

<div class="mb-3">
    <label>Job Title</label>
    <input type="text" class="form-control border-gray-300..." name="title">
</div>

B. Tables (Dynamic Wrapper)
Use the <x-table> component for the responsive shell and headers. Write the <tr> logic manually for flexibility.
Correct:

Blade

<x-table :headers="['Job Title', 'Status', 'Applied Date', 'Actions']">
    @foreach($jobs as $job)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                {{ $job->title }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                <x-badge :color="$job->status === 'active' ? 'green' : 'red'">
                    {{ $job->status }}
                </x-badge>
            </td>
            </tr>
    @endforeach
</x-table>

C. Toast Messages (SheafUI Style)
Pass messages via session flash. Catch them in layouts/app.blade.php.
Controller:

PHP

return redirect()->route('dashboard')->with('success', 'Campaign started successfully!');

D. Charts (ApexCharts)
Pass data from the Controller to the View. Do not query the DB inside the View.
View Implementation:

HTML

<div id="chart"></div>
<script>
    var options = {
        series: @json($chartData),
        chart: { type: 'bar', height: 350 },
        // ...
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

4. UI/UX Guidelines
   Typography: Headings (font-bold text-gray-800 dark:text-white), Body (text-sm text-gray-600 dark:text-gray-400).
   Spacing: Standard vertical spacing is mb-4 or gap-6.
   Responsiveness: All layouts must work on mobile (flex-col) and desktop (md:flex-row).
5. Workflow Trigger
   When asked to create a new module (e.g., "Create the Leads module"):
   Define the Model & Migration.
   Define the Route.
   Create the Controller using standard CRUD.
   Create the View using ONLY the components defined above.
   If a UI element is repeated twice, Refactor it into a component immediately.
