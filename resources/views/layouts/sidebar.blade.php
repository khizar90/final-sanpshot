<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class=""><img src="/assets/img/logo.png" alt="" width="30" height="50"></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
         <li class="menu-item {{(Request::url() == route('dashboard-home'))? 'active':''}}">
            <a href="{{ route('dashboard-home') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Home">Home</div>
            </a>
            
        </li>

       
       
{{--

    



        <li class="menu-item {{(Request::url() == route('dashboard-schedules'))? 'active':''}}">
            <a href="{{ route('dashboard-schedules') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-calendar"></i>
                <div data-i18n="Schedules">Schedules</div>
            </a>
        </li>

        <li class="menu-item {{(Request::url() == route('dashboard-bus-images'))? 'active':''}}">
            <a href="{{ route('dashboard-bus-images') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-album"></i>
                <div data-i18n="Bus Images">Bus Images</div>
            </a>
        </li> --}}

        <li class="menu-item {{(Request::url() == route('dashboard-user-'))? 'active':''}} || {{ Str::contains(Request::url(), 'dashboard/user') ? 'active' : '' }}">
            <a href="{{ route('dashboard-user-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>

        
        <li class="menu-item {{(Request::url() == route('dashboard-level-'))? 'active':''}} ">
            <a href="{{ route('dashboard-level-') }}" class="menu-link">
                <i class="menu-icon ti ti-stack-pop"></i>
                <div data-i18n="Levels">Levels</div>
            </a>
        </li>

        <li class="menu-item {{(Request::url() == route('dashboard-message-'))? 'active':''}}">
            <a href="{{ route('dashboard-message-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-message"></i>
                <div data-i18n="Admin Support">Admin Support</div>
            </a>
        </li>
        
        <li class="menu-item {{(Request::url() == route('dashboard-task-list'))? 'open':''}}  || {{(Request::url() == route('dashboard-task-add'))? 'open':''}} || {{ Str::contains(Request::url(), 'dashboard/task/list') ? 'open':'' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-brand-asana"></i>
                <div data-i18n="Task">Task</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{(Request::url() == route('dashboard-task-add'))? 'active':''}} ">
                    <a href="{{ route('dashboard-task-add') }}" class="menu-link">
                        <div>Add Task</div>
                    </a>
                </li>   
                <li class="menu-item {{(Request::url() == route('dashboard-task-list'))? 'active':''}}  || {{ Str::contains(Request::url(), 'dashboard/task/list') ? 'active' : '' }}">
                    <a href="{{ route('dashboard-task-list') }}" class="menu-link">
                        <div>List Tasks</div>
                    </a>
                </li> 
                
              
            </ul>
        </li>




        <li class="menu-item {{(Request::url() == route('dashboard-question-'))? 'active':''}}">
            <a href="{{ route('dashboard-question-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-help"></i>
                <div data-i18n="Question's">Question's</div>
            </a>
        </li>
        <li class="menu-item {{(Request::url() == route('dashboard-help-'))? 'active':''}}">
            <a href="{{ route('dashboard-help-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-video"></i>
                <div data-i18n="Help Videos">Help Videos</div>
            </a>
        </li>

        <li class="menu-item {{(Request::url() == route('dashboard-faq-'))? 'active':''}}">
            <a href="{{ route('dashboard-faq-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-help"></i>
                <div data-i18n="FAQ'S">FAQ'S</div>
            </a>
        </li>

        <li class="menu-item {{(Request::url() == route('dashboard-point-value'))? 'active':''}}">
            <a href="{{ route('dashboard-point-value') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-help"></i>
                <div data-i18n="SnapShot Point">SnapShot Point</div>
            </a>
        </li>

        <li class="menu-item {{(Request::url() == route('dashboard-logout'))? 'active':''}} ">
            <a href="{{ route('dashboard-logout') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-logout"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
        </li> 

    </ul>
</aside>
