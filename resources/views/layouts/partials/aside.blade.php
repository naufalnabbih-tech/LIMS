 <!-- Clean Sidebar -->
 <div class="flex h-full flex-col bg-white">
     <!-- Logo -->
     <div class="flex h-16 items-center px-6 border-b border-gray-100">
         <div class="flex items-center">
             <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                 <span class="text-white font-bold text-sm">L</span>
             </div>
             <span class="ml-3 text-xl font-bold text-gray-900">LIMS</span>
         </div>
     </div>

     <!-- Navigation -->
     <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
         <!-- Dashboard -->
         <a href="{{ route('dashboard') }}" wire:navigate
             class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
             <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
             </svg>
             Dashboard
         </a>

         <!-- Sample Raw Material Submission -->
         <a href="{{ route('sample-rawmat-submissions') }}" wire:navigate
             class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sample-rawmat-submissions') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
             <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
             </svg>
             Raw Material Sample Submission
         </a>

         <!-- Materials Dropdown -->
         <div class="pt-4 cursor-pointer" x-data="{ open: {{ request()->routeIs('rawmat-categories', 'rawmats', 'specifications', 'references') ? 'true' : 'false' }} }">
             <button @click="open = !open"
                 class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md cursor-pointer {{ request()->routeIs('rawmat-categories', 'rawmats', 'specifications', 'references') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                 <div class="flex items-center">
                     <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                     </svg>
                     Raw Material Databook
                 </div>
                 <svg class="h-4 w-4 transform transition-transform duration-150" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                 </svg>
             </button>

             <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-2 ml-4 space-y-1">

                 <a href="{{ route('rawmat-categories') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('rawmat-categories') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                     </svg>
                     Categories
                 </a>

                 <a href="{{ route('rawmats') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('rawmats') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                     </svg>
                     Raw Materials
                 </a>

                 <a href="{{ route('specifications') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('specifications') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h9a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                     </svg>
                     Specifications
                 </a>

                 <a href="{{ route('references') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('references') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                     </svg>
                     References
                 </a>
             </div>
         </div>

         <!-- Solder Databook Dropdown -->
         <div class="pt-4 cursor-pointer" x-data="{ open: {{ request()->routeIs('solder-categories', 'solders', 'solder-specifications', 'solder-references') ? 'true' : 'false' }} }">
             <button @click="open = !open"
                 class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md cursor-pointer {{ request()->routeIs('solder-categories', 'solders', 'solder-specifications', 'solder-references') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                 <div class="flex items-center">
                     <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M13 10V3L4 14h7v7l9-11h-7z" />
                     </svg>
                     Solder Databook
                 </div>
                 <svg class="h-4 w-4 transform transition-transform duration-150" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                 </svg>
             </button>

             <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2" class="mt-2 ml-4 space-y-1">

                 <a href="{{ route('solder-categories') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('solder-categories') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                     </svg>
                     Categories
                 </a>

                 <a href="{{ route('solders') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('solders') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M13 10V3L4 14h7v7l9-11h-7z" />
                     </svg>
                     Solders
                 </a>

                 <a href="{{ route('solder-specifications') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('solder-specifications') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h9a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                     </svg>
                     Specifications
                 </a>

                 <a href="{{ route('solder-references') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('solder-references') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                     </svg>
                     References
                 </a>
             </div>
         </div>

         <!-- System Management Dropdown -->
         <div class="pt-4 cursor-pointer" x-data="{ open: {{ request()->routeIs('users', 'roles', 'instruments', 'thermohygrometers') ? 'true' : 'false' }} }">
             <button @click="open = !open"
                 class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md cursor-pointer {{ request()->routeIs('users', 'roles', 'instruments', 'thermohygrometers') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                 <div class="flex items-center">
                     <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                     </svg>
                     System Management
                 </div>
                 <svg class="h-4 w-4 transform transition-transform duration-150" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                 </svg>
             </button>

             <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2" class="mt-2 ml-4 space-y-1">

                 <a href="{{ route('users') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                     </svg>
                     User Management
                 </a>

                 <a href="{{ route('roles') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('roles') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                     </svg>
                     Role Management
                 </a>

                 <a href="{{ route('instruments') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('instruments') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                     </svg>
                     Instrument Management
                 </a>

                 <a href="{{ route('thermohygrometers') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('thermohygrometers') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M13 10V3L4 14h7v7l9-11h-7z" />
                     </svg>
                     Thermohygrometer Management
                 </a>


             </div>
         </div>

         <!-- Condition Dropdown -->
         <div class="pt-4 cursor-pointer" x-data="{ open: {{ request()->routeIs('instrument-conditions', 'thermohygrometer-conditions') ? 'true' : 'false' }} }">
             <button @click="open = !open"
                 class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md cursor-pointer {{ request()->routeIs('instrument-conditions', 'thermohygrometer-conditions') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                 <div class="flex items-center">
                     <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                     </svg>
                     Condition Data
                 </div>
                 <svg class="h-4 w-4 transform transition-transform duration-150" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                 </svg>
             </button>

             <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2" class="mt-2 ml-4 space-y-1">

                 <a href="{{ route('instrument-conditions') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('instrument-conditions') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                     </svg>
                     Instrument Condition
                 </a>
                 <a href="{{ route('thermohygrometer-conditions') }}" wire:navigate
                     class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('thermohygrometer-conditions') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150">
                     <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                     </svg>
                     Thermohygrometer Conditions
                 </a>
             </div>
         </div>
     </nav>

     <!-- User Profile -->
     <div class="border-t border-gray-100 p-4">
         <div class="flex items-center">
             <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                 <span
                     class="text-gray-700 font-medium text-sm">{{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}</span>
             </div>
             <div class="ml-3">
                 <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                 <p class="text-xs text-gray-500">
                     {{ auth()->user() && auth()->user()->role ? auth()->user()->role->display_name : 'User' }}</p>
             </div>
         </div>
     </div>
 </div>
