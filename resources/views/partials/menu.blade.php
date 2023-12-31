<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route("home") }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("calendar.index") }}" class="nav-link">
                    <i class="fa-fw fas fa-calendar nav-icon">

                    </i>
                    Calendar
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("students.index") }}" class="nav-link">
                    <i class="fa-fw fas fa-user nav-icon">

                    </i>
                    Students
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("classes.index") }}" class="nav-link">
                    <i class="fa-fw fas fa-users nav-icon">

                    </i>
                    Classes
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("logout") }}" class="nav-link">
                    <i class="fa-fw fa fa-sign-out nav-icon">

                    </i>
                    Logout
                </a>
            </li>

        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>