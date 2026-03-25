<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('backend.dashboard.index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ $settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png') }}"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $settings->logo ? asset($settings->logo) : asset('assets/images/logo-dark.png') }}"
                    alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('backend.dashboard.index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ $settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png') }}"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $settings->logo ? asset($settings->logo) : asset('assets/images/logo-light.png') }}"
                    alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                {{-- <li class="nav-item">
                    <a href="{{ route('backend.dashboard.index') }}"
                        class="nav-link menu-link  {{ getPageStatus('backend.dashboard.*', 'collapsed active') }}"
                        href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                    <div class="collapse menu-dropdown {{getPageStatus('backend.dashboard.*', 'show')}}" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('backend.dashboard.index')}}" class="nav-link {{getPageStatus('backend.dashboard.index')}}" data-key="t-ecommerce"> Home </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link menu-link  {{ getPageStatus('backend.dashboard.*', 'collapsed active') }}"
                        href="{{ route('backend.dashboard.index') }}" role="button">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.system-user.*') ? 'active' : '' }}"
                        href="{{ route('backend.system-user.index') }}">
                        <i class="ri-group-line"></i> <span>Users</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.feature.category.*') ? 'active' : '' }}"
                        href="{{ route('backend.feature.category.index') }}">
                        <i class="ri-dashboard-line"></i> <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.products.*') ? 'active' : '' }}"
                        href="{{ route('backend.products.index') }}">
                        <i class="ri-store-2-line"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.spotlight.*') ? 'active' : '' }}"
                        href="#sidebarSpotlight" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarSpotlight">
                        <i class="ri-flashlight-line"></i>
                        <span>Boost Payments</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('backend.spotlight.*') ? 'show' : '' }}"
                        id="sidebarSpotlight">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.spotlight.index') }}"
                                    class="nav-link {{ request()->routeIs('backend.spotlight.index') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.spotlight.cities') }}"
                                    class="nav-link {{ request()->routeIs('backend.spotlight.cities') }}">City
                                    Analytics</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.garage.*') ? 'active' : '' }}"
                        href="#sidebarGarage" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarGarage">
                        <i class="ri-home-gear-line"></i>
                        <span>Garage Sales</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('backend.garage.*') ? 'show' : '' }}"
                        id="sidebarGarage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.garage.index') }}"
                                    class="nav-link {{ request()->routeIs('backend.garage.index') }}">All Events</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.garage.analytics') }}"
                                    class="nav-link {{ request()->routeIs('backend.garage.analytics') }}">Analytics</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.messaging.*') ? 'active' : '' }}"
                        href="#sidebarMessaging" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarMessaging">
                        <i class="ri-chat-3-line"></i>
                        <span>Messaging</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('backend.messaging.*') ? 'show' : '' }}"
                        id="sidebarMessaging">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.messaging.conversations') }}"
                                    class="nav-link {{ request()->routeIs('backend.messaging.conversations') }}">Conversations</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.messaging.pickups') }}"
                                    class="nav-link {{ request()->routeIs('backend.messaging.pickups') }}">Pickup
                                    Requests</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.messaging.analytics') }}"
                                    class="nav-link {{ request()->routeIs('backend.messaging.analytics') }}">Analytics</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.test_realtime') ? 'active' : '' }}"
                        href="{{ route('backend.test_realtime') }}">
                        <i class="ri-broadcast-line"></i> <span>Realtime Test</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link  {{ getPageStatus('backend.dashboard.*', 'collapsed active') }}"
                        href="#sidebarDashboards" role="button">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Something</span>
                    </a>
                </li> --}}
                <!-- end Dashboard Menu -->


                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus('backend.help.*', 'collapsed active') }}"
                        href="#sidebarPagesHelp" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarPages">
                        <i class="ri-pages-line"></i> <span data-key="t-pages">Help & Support</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus('backend.help.*', 'show') }}"
                        id="sidebarPagesHelp">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.feature.faq.index') }}"
                                    class="nav-link {{ getPageStatus('backend.feature.faq.*') }}">
                                    FAQ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.feature.terms.index') }}"
                                    class="nav-link {{ getPageStatus('backend.feature.terms.*') }}">
                                    Terms and conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.feature.contacts.index') }}"
                                    class="nav-link {{ getPageStatus('backend.feature.contacts.*') }}">
                                    Contact Messages
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>



                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus('backend.page.*', 'collapsed active') }}"
                        href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarPages">
                        <i class="ri-pages-line"></i> <span data-key="t-pages">Pages</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus('backend.page.*', 'show') }}"
                        id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.page.index') }}"
                                    class="nav-link {{ getPageStatus('backend.page.*') }}" data-key="t-starter"> All
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus('backend.settings.*') }}" href="#sidebarMultilevel"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus('backend.settings.*', 'show') }}"
                        id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.profile.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.profile.*') }}"
                                    data-key="t-level-1.1"> Profile Settings </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.system.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.system.*') }}"
                                    data-key="t-level-1.1"> System Settings </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.mail.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.mail.*') }}"
                                    data-key="t-level-1.1"> Mail Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.payments.stripe.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.payments.*') }}"
                                    data-key="t-level-1.1"> Payment Settings</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
