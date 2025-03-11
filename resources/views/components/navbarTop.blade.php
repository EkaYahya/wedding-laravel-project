<nav class="bg-primary-dark shadow-md fixed top-0 left-0 right-0 z-10 py-3 flex items-center justify-between">
    <div class="container mx-auto flex justify-between py-2 max-w-screen-md">   
        <!-- Logo -->
        <div class="flex items-center">
            <img src="{{ asset('favicon.svg') }}" alt="Logo" class="h-8 w-8">
            <span class="ml-2 text-xl font-bold text-white">Digital Wedding</span>
        </div>

        <!-- Burger Menu -->
        <div class="relative">
            <button id="burger-menu" class="focus:outline-none">
                <i class="fas fa-bars text-2xl text-white"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdown-menu" 
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-20">
                <ul class="py-2">
                    <!-- Send WA -->
                    <li>
                        <a href="{{ route('showTamu') }}" class="block px-4 py-2 text-sm text-primary-dark hover:bg-gray-100">
                            <i class="fas fa-paper-plane mr-2"></i> Send WA
                        </a>
                    </li>
                    <!-- Setting -->
                    <li>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-primary-dark hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Setting
                        </a>
                    </li>
                    <!-- Logout -->
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-primary-dark hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('burger-menu').addEventListener('click', function () {
        const dropdown = document.getElementById('dropdown-menu');
        dropdown.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        const menu = document.getElementById('dropdown-menu');
        const button = document.getElementById('burger-menu');
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
