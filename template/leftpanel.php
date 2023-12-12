<aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default " >

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="../oph.php"><img src="../images/chronos_logoadied.png" alt="Logo"></a>
                <a class="navbar-brand hidden" href="../oph.php"><img src="../images/faviconku.png" alt="Logo"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="">
                        <a href="../oph.php"> <i class="menu-icon fa fa-desktop"></i>Dashboard </a>  
                    </li>
                    <li>
                        <a href="home"> <i class="menu-icon fa fa-home"></i>Home </a>                      
                    </li>
                    <!-- <li>
                        <a href="perf_history.php"> <i class="menu-icon fa fa-bar-chart-o"></i>Performance History </a>                      
                    </li> -->
                    <h3 class="menu-title">Output Per Hours</h3><!-- /.menu-title -->
                    <li class="" <?php if($_SESSION['level'] == 0){echo "hidden"; }?>>
                    <a href="oph"> <i class="menu-icon fa fa-list-ol"></i>Detail Data </a>                        
                    </li>
                    <li class="" <?php if($_SESSION['level'] == 1){echo "hidden"; }?>>
                    <a href="oph_summary"><span class="badge badge-primary float-right"><!--isibadge--></span><i class="menu-icon fa fa-list-alt"></i>Summary Data </a>
                    </li>
                    <li>                        
                        <a href="chart_oph" > <i class="menu-icon fa fa-bar-chart-o"></i>Chart OPH</a>
                    </li>

                    <h3 class="menu-title">Productivity Per Hours</h3>
                    <li class="">
                        <a href="pph" > <i class="menu-icon fa fa-table"></i>Detail Data PPH</a>
                    </li>
                    <li>
                        <a href="chart_pph" > <i class="menu-icon fa fa-bar-chart-o "></i>Chart PPH</a>                        
                    </li>

                    <h3 class="menu-title">Inventory</h3>
                    <li>
                        <a href="fg"> <i class="menu-icon fa fa-table"></i>Finsih Good</a>
                    </li>
                    <li>
                        <a href="wip"> <i class="menu-icon fa fa-table"></i>Data WIP </a>
                    </li>
                    <li>
                        <a href="onhand"> <i class="menu-icon fa fa-table"></i>Data onhand </a>
                    </li>
                    <!-- <li>
                        <a href="wip_detail.php"> <i class="menu-icon fa fa-list-ol"></i>Data detail </a>
                    </li> -->

                    <!-- <h3 class="menu-title">Inventory</h3>
                    <li>
                        <a href="wip"> <i class="menu-icon fa fa-table"></i>Data WIP </a>
                        <a href="wip_detail.php"> <i class="menu-icon fa fa-list-ol"></i>Data detail </a>
                    </li> -->
                    <h3 class="menu-title">Report</h3>
                    <li class="">
                        <a href="report"> <i class="menu-icon fa fa-file-text"></i>Tracking Report</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>