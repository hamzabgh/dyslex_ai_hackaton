<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="navbar-horizontal">
    <!--     BEGIN HEAD TAG     -->
       @include('layouts.base.header')
    <!--       END HEAD TAG     -->
    <body>

        <main class="main" id="top">
                <!--     BEGIN TOP NAVBAR TAG     -->  
                @include('layouts.base.menu2')
                <!--     END TOP NAVBAR TAG     -->
                @include('layouts.base.topnavbarbig')
                
                <div class="content">
                    <!-- ===============================================-->
                    <!--    Main Content                                -->
                    <!-- ===============================================--> 
                    <div id="mainContent">
                        <!--     DYNAMIC LOADING OF APP PAGES     -->
                       @yield('content')
                    
                       @include('layouts.base.contentfooter')   
                    </div>
                    <!-- ===============================================-->
                    <!--    End of Main Content-->
                    <!-- ===============================================-->

                    <!--     BEGIN TOP NAVBAR TAG     --> 

                    <!--     BEGIN CONTENT FOOTER TAG     --> 

                    
                    <!--     BEGIN CONTENT FOOTER TAG     --> 
                </div>
                @include('layouts.base.support_chat')
        </main>
        <!--     BEGIN PAGE FOOTER TAG     --> 
         <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
        @include('layouts.base.footer')
        <!--     BEGIN PAGE FOOTER TAG     --> 
    </body>

</html>
