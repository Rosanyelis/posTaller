
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <img src="{{ asset('assets/images/rey-neumatico-.png') }}" alt="logo" height="73"> <span class="logo-txt"></span>
                            </a>

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                <img src="{{ asset('assets/images/rey-neumatico-.png') }}" alt="logo" height="73"> <span class="logo-txt"></span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">


                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div>


                        <!-- <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item right-bar-toggle me-2">
                                <i data-feather="settings" class="icon-lg"></i>
                            </button>
                        </div> -->

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="rounded-circle header-profile-user"> <i class="fas fa-user"></i></span>

                                <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="apps-contacts-profile.html"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile</a>
                                <a class="dropdown-item" href="auth-lock-screen.html"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock screen</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                                        Desconectar
                                    </a>
                                </form>
                            </div>
                        </div>

                    </div>
