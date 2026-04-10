<style>
    /* Dashboard */
    .bi-house-door { color: #ff6b6b !important; }

    /* Home */
    .bi-house { color: #4dabf7 !important; }

    /* About */
    .bi-person-badge { color: #51cf66 !important; }

    /* Resume */
    .bi-file-earmark-text { color: #f59f00 !important; }

    /* Page Titles */
    .bi-card-heading { color: #845ef7 !important; }

    /* Footer */
    .bi-layout-text-window { color: #20c997 !important; }

    /* SEO */
    .bi-search { color: #e64980 !important; }

    /* Settings */
    .bi-gear { color: #6f757a !important; }
</style>
{{-- Right Sidebar --}}
<div class="right-sidebar">
    <div class="sidebar-title">
        <h3 class="weight-600 font-16 text-blue">
            Layout Settings
            <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
        </h3>
        <div class="close-sidebar" data-toggle="right-sidebar-close">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>
    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content">
            <h4 class="weight-600 font-18 pb-10">Header Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
            </div>

            <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
            </div>

            <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
            <div class="sidebar-radio-group pb-10 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-1" checked="" />
                    <label class="custom-control-label" for="sidebaricon-1"><i
                            class="fa fa-angle-down"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-2" />
                    <label class="custom-control-label" for="sidebaricon-2"><i
                            class="ion-plus-round"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-3" />
                    <label class="custom-control-label" for="sidebaricon-3"><i
                            class="fa fa-angle-double-right"></i></label>
                </div>
            </div>

            <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
            <div class="sidebar-radio-group pb-30 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-1" checked="" />
                    <label class="custom-control-label" for="sidebariconlist-1"><i
                            class="ion-minus-round"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-2" />
                    <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                            aria-hidden="true"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-3" />
                    <label class="custom-control-label" for="sidebariconlist-3"><i
                            class="dw dw-check"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-4" checked="" />
                    <label class="custom-control-label" for="sidebariconlist-4"><i
                            class="icon-copy dw dw-next-2"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-5" />
                    <label class="custom-control-label" for="sidebariconlist-5"><i
                            class="dw dw-fast-forward-1"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-6" />
                    <label class="custom-control-label" for="sidebariconlist-6"><i
                            class="dw dw-next"></i></label>
                </div>
            </div>

            <div class="reset-options pt-30 text-center">
                <button class="btn btn-danger" id="reset-settings">
                    Reset Settings
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Left Sidebar --}}
<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('/backend/assets/img/logo/abhishek-potfolio_white_logo.webp') }}" alt="" class="white-logo" />
            {{-- <img src="{{ asset('/backend/assets/img/logo/abhishek-potfolio_black_logo.webp') }}" alt="" class="dark-logo" /> --}}
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                    class="dropdown-toggle no-arrow {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="micon bi bi-house-door"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>

                {{-- Home Management --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-house"></span>
                        <span class="mtext">Home Management</span>
                    </a>

                    <ul class="submenu {{ request()->routeIs('hero.*') ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('hero.index') }}" 
                            class="{{ request()->routeIs('hero.*') ? 'active' : '' }}">
                                Hero Section
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- About Us Management --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-person-badge"></span>
                        <span class="mtext">About Us Management</span>
                    </a>

                    @php
                        $aboutRoutes = ['about.*', 'skills.*', 'stats.*', 'features.*'];
                        $isAboutActive = collect($aboutRoutes)->contains(fn($route) => request()->routeIs($route));
                    @endphp

                    <ul class="submenu {{ $isAboutActive ? 'show' : '' }}">

                        <li>
                            <a href="{{ route('about.index') }}" 
                            class="{{ request()->routeIs('about.*') ? 'active' : '' }}">
                                About Info
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('stats.index') }}" 
                            class="{{ request()->routeIs('stats.*') ? 'active' : '' }}">
                                Stats (Counters)
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('skills.index') }}" 
                            class="{{ request()->routeIs('skills.*') ? 'active' : '' }}">
                                Skills
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('features.index') }}" 
                            class="{{ request()->routeIs('features.*') ? 'active' : '' }}">
                                Features
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Resume Management --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-file-earmark-text"></span>
                        <span class="mtext">Resume Management</span>
                    </a>

                    <ul class="submenu {{ request()->routeIs('resume.*') ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('resume.index') }}" 
                            class="{{ request()->routeIs('resume.*') ? 'active' : '' }}">
                                Resume Info
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Page Titles (SEO Content) --}}
                <li>
                    <a href="{{ route('page-titles.index') }}" 
                    class="dropdown-toggle no-arrow {{ request()->routeIs('page-titles.*') ? 'active' : '' }}">
                        <span class="micon bi bi-card-heading"></span>
                        <span class="mtext">Page Titles</span>
                    </a>
                </li>

                {{-- Footer Management --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-layout-text-window"></span>
                        <span class="mtext">Footer Management</span>
                    </a>

                    <ul class="submenu 
                        {{ request()->routeIs('brand-description.*') || 
                        request()->routeIs('social-links.*') || 
                        request()->routeIs('contacts.*') || 
                        request()->routeIs('copyrights.*') ? 'show' : '' }}">

                        <li>
                            <a href="{{ route('brand-description.index') }}" 
                            class="{{ request()->routeIs('brand-description.*') ? 'active' : '' }}">
                                Brand Description
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('social-links.index') }}" 
                            class="{{ request()->routeIs('social-links.*') ? 'active' : '' }}">
                                Social Links
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('contacts.index') }}" 
                            class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                                Contacts
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('copyrights.index') }}" 
                            class="{{ request()->routeIs('copyrights.*') ? 'active' : '' }}">
                                Copyrights
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- SEO Settings --}}
                <li>
                    <a href="{{ route('seo-settings.index') }}" 
                    class="dropdown-toggle no-arrow {{ request()->routeIs('seo-settings.*') ? 'active' : '' }}">
                        <span class="micon bi bi-search"></span>
                        <span class="mtext">SEO Settings</span>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-gear"></span>
                        <span class="mtext">Settings</span>
                    </a>

                    <ul class="submenu {{ request()->routeIs('change-password') || request()->routeIs('admin.profile') ? 'show' : '' }}">
                        
                        <li>
                            <a href="{{ route('change-password') }}" 
                            class="{{ request()->routeIs('change-password') ? 'active' : '' }}">
                                Change Password
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.profile') }}" 
                            class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                                Profile
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="mobile-menu-overlay"></div>
