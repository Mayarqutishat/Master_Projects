<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <h4 class="ml-5"> ALMA </h4>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text border-0 mdi mdi-magnify"></span>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
                </div>
            </form>
        </div>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown">
                    <div class="nav-profile-img">
                        <!-- Correct dynamic image rendering based on the User model -->
                        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/faces/face1.jpg') }}" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <!-- Display the logged-in user's name dynamically -->
                        <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                        <!-- Display the user's role dynamically -->
                        <p class="mb-1 text-black" style="font-size: 12px; color: #6c757d;">
                            @if(Auth::user()->isAdmin()) Admin 
                            @elseif(Auth::user()->isCustomer()) Customer
                            @else Technician @endif
                        </p>
                    </div>
                    <!-- Updated dropdown arrow -->
                  
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                
                    <!-- Link for logout -->
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                    </a>
                    <!-- Add logout form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>

    </div>
</nav>

