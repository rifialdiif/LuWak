<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body class="loading"
    data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "light"}, "showRightSidebarOnPageLoad": true}'>
    <!-- Begin page -->
    <div id="wrapper">

        @include('layouts.header')
        @include('layouts.navigation')

        <div class="content-page">
            <div class="content">

                @include('layouts.modal')

                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            @include('layouts.footer')

        </div>
    </div>

    @include('layouts.settings')

    <div class="rightbar-overlay"></div>

    @include('layouts.foot')

</body>

</html>
